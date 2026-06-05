<?php

require_once("../utils/defines.php");
require_once("../class/instagrowth.class.php");
require_once("../class/mysql.class.php");






class InstagramUsernameCleaner
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Find malformed Instagram usernames for a given instagram_username_interact.
     *
     * @param string $interactName
     * @return array
     */
    public function findMalformedUsernames(string $interactName): array
    {
        $sql = "
            SELECT 
                id,
                instagram_username AS instagram_username_bad,
                instagram_username_interact,
                target,
                date_created
            FROM instagram_notifications
            WHERE 
                instagram_username LIKE '% %'
                AND instagram_username_interact = :interactName
                AND (target IS NULL OR target = '')
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':interactName', $interactName, PDO::PARAM_STR);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $results = [];

        foreach ($rows as $row) {
            $bad = $row['instagram_username_bad'];
            $corrected = $this->correctUsername($bad);

            $date = date('Y-m-d', strtotime($row['date_created'])); // only the date
            $target = $this->getAssociatedTarget($corrected, $interactName, $date);

            // Update the row in instagram_notifications
	    $updateSql = "
		UPDATE instagram_notifications
			SET instagram_username = :correctedUsername,
    			target = COALESCE(:targetParam, target),
    			is_follow_back = CASE 
        			WHEN :targetParam IS NOT NULL THEN 1
        			ELSE is_follow_back
    				END,
    			last_interaction = CASE 
        			WHEN :targetParam IS NOT NULL THEN date_created
        			ELSE last_interaction
    				END
			WHERE id = :id
			AND instagram_username_interact = :interactName

            ";

            $updateStmt = $this->db->prepare($updateSql);

            $updateStmt->bindValue(':correctedUsername', $corrected, PDO::PARAM_STR);
            $updateStmt->bindValue(':id', $row['id'], PDO::PARAM_INT);
            $updateStmt->bindValue(':interactName', $interactName, PDO::PARAM_STR);

            // If $target is null, pass null — COALESCE ensures existing value is kept
	    //$updateStmt->bindValue(':targetParam', $target, PDO::PARAM_STR);
	    $updateStmt->bindValue(
    			':targetParam',
   			 $target,
    			$target === null ? PDO::PARAM_NULL : PDO::PARAM_STR
	    );

            $updateStmt->execute();


            $results[] = [
                'id'                         => $row['id'],
                'instagram_username_bad'     => $bad,
                'instagram_username_correct' => $corrected,
                'instagram_username_interact'=> $row['instagram_username_interact'],
                'target'                     => $row['target'],
                'date_created'               => $row['date_created'],
                'target'                     => $target
            ];
        }

        return $results;
    }

    /**
     * Clean an Instagram username (remove parentheses, remove substring after space)
     */
    private function correctUsername(?string $username): string
    {
        if ($username === null) {
            return '';
        }

        $clean = $username;

        // Remove parentheses and their contents
        $clean = preg_replace('/\s*\(.*?\)/', '', $clean);

        // Remove everything after the first space
        if (strpos($clean, ' ') !== false) {
            $clean = substr($clean, 0, strpos($clean, ' '));
        }

        return trim($clean);
    }

    /**
     * Retrieve the associated target from instagram_interact
     *
     * @param string $correctedUsername
     * @param string $interactName
     * @param string $dateCreated Format: 'YYYY-MM-DD'
     * @return string|null
     */
    public function getAssociatedTarget(string $correctedUsername, string $interactName, string $dateCreated): ?string
    {
        $sql = "
            SELECT target
            FROM instagram_interact
            WHERE instagram_username = :username
            AND instagram_username_interact = :interact
            
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $correctedUsername, PDO::PARAM_STR);
        $stmt->bindValue(':interact', $interactName, PDO::PARAM_STR);
        //$stmt->bindValue(':date', $dateCreated, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['target'] ?? null;
    }

}



// Usage
try {

    // Check that the Instagram username is provided
    if ($argc < 2) {
        throw new InvalidArgumentException("Usage: php clean.php instagram_username");
    }
    $instagramUsernameInteract = $argv[1];
    $db = new PDO(
        'mysql:host='.$DBHOST.';dbname='.$DBNAME.';charset=utf8mb4',
        $DBUSER,
        $DBPASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    #$debug = getenv('DEBUG') === 'true';
    $debug = true;
    $cleaner = new InstagramUsernameCleaner($db);
    $badEntries = $cleaner->findMalformedUsernames($instagramUsernameInteract);

    print_r($badEntries);


} catch (Exception $e) {
    print("Configuration error: " . $e->getMessage() . "\n");
    //http_response_code(500);
    echo json_encode(['error' => 'Server configuration error']);
}
