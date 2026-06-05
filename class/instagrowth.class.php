<?php

include_once("../utils/defines.php");

class MInstagrowth
{
	
	public function updateinstagrowthmailing($cstring,$cid)
	{
		$sql = "update user_instagram_profil set mailing=NOW() where id='" . $cid . "'"; 
		#echo " La requete " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		return $result;
		
	}

	public function updatestripesession($cstring,$cid,$stripe_session)
	{
		$sql = "update user set stripe_session='" . $stripe_session . "'  where id='" . $cid . "'";
		$result = mysqli_query($cstring,$sql);
		return $result;
	}

	public function updateinstagrowthmasterclass($cstring,$cid)
	{
		$sql = "update user_instagram_profil set mailing=NOW(),mailing_masterclass=NOW() where id='" . $cid . "'"; 
		#echo " La requete " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		return $result;
		
	}


	public function resetstripecustomer($cstring, $customer_id)
	{
		$sql = "update user set is_active=O  where stripe_customer_id='" . $customer_id . "'"; 
		$result = mysqli_query($cstring,$sql);
		return $result;
	}



	public function insertnewperiodabo($cstring,$user_id,$montant_ttc,$montant_ht,$ref_abo)
	{
	
		$sql = "insert into user_abo_paiement (user_id,montant_ttc,montant_ht,ref_abo,date_start,date_end) values ('".$user_id."','". $montant_ttc."','" . $montant_ht . "','" . $ref_abo . "',NOW(),DATE_ADD(NOW(),INTERVAL 1 MONTH))";
		
		echo "Requete SQL " . $sql . "\n";
		#exit();
		$result = mysqli_query($cstring,$sql);
		$mid = mysqli_insert_id($cstring);
		return $mid;

	}



	public function checkcardinfo($cstring,$user_id)
	{
	
		$sql = "select customer_id from user_card_info where user_id='" . $user_id . "'";
		#echo "ici";exit();
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row)
		{
			if ($row[0])
			{
				return row[0];
			}
		}
		return -1;
	}


	public function checkabo($cstring,$user_id)
	{
	
		$sql = "select is_active from user_abo_info where user_id='" . $user_id . "'";
		#echo "ici";exit();
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row)
		{
		//echo " ROW " . $row[0];exit();
			if ($row[0])
			{
				return row[0];
			}
		}
		return -1;
	}



	public function checkdoublonsendmail($cstring,$email)
	{
	
		//$sql = "select count(*) from user_instagram_profil where email='" . $email . "' and mailing is not null";
		$sql = "select count(*) from user_instagram_profil where email='" . $email . "' and mailing is not null and DATEDIFF(NOW(),mailing) <= 1";
		#echo "ici";exit();
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row)
		{
			if ($row[0])
			{
				return $row[0];
			}
		}
		return 0;
	}


	public function checkdoublonmasterclass($cstring,$email)
	{
	
		$sql = "select count(*) from user_instagram_profil where email='" . $email . "' and mailing_masterclass is not null"; 
		#echo "ici";exit();
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row)
		{
		echo "email " . $email;
			if ($row[0])
			{
			echo "Doublon " . $email . "\n";
				return $row[0];
			}
		}
		return 0;
	}


	public function getinstagrowthsendmail($cstring)
	{
	
		$sql = "select email,name,id from user_instagram_profil where  (email not like '%@%.ru' and email not like '%@%.it' and email not like '%@%.pl' and email not like '%@%.es' and email not like '%.pt') and email is not null and email!=''  and mailing is null and is_unsubscribe is null and is_good_email=1 and id > 200000";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}




	public function getinstagrowthEnglishsendmail($cstring)
	{
	
		$sql = "select email,name,id from user_instagram_profil where ((email like '%.ru%') or (email like '%.de%') or (email like  '%.it%') or (email like '%.br%') or (email like '%.uk%')  ) and mailing is null and is_unsubscribe is null";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}



	public function getinstagrowthResendEnglishsendmail($cstring)
	{
	
		$sql = "select email,name,id from user_instagram_profil where ((email like '%.ru%') or (email like '%.de%') or (email like  '%.it%') or (email like '%.br%') or (email like '%.uk%')  ) and (date_update < DATE_SUB(NOW(), INTERVAL 90 day)) and mailing is not null  and is_unsubscribe is  null";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}




	public function getinstagrowthFrenchsendmail($cstring)
	{

		$sql = "select email,name,id from user_instagram_profil where (email like '%math%' or user_bio like '%influenceu%' or user_bio like '%partnariat%' or user_bio like '%agence%' or user_bio like '%france%' or user_bio like '%paris%' or user_bio like '%toulouse%' or user_bio like '%bonjour%' or email like '%.gp%' or email like '%.fr%' or email like '%clemence%' or email like '%carole%' or email like '%stephane%' or email like '%jeanne%' or email like '%guillaume%' or email like '%axelle%' or email like '%francois%' or email like '%laetitia%') and (mailing is null) and (email!='' ) and is_unsubscribe is null and is_good_email=1";
		//$sql = "select email,name,id from user_instagram_profil where  email is not null and email!=''  and mailing is null and id>'275854' ";
		#echo "Requete SQL " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		return $result;

	}


	public function getinstagrowthRESENDFrenchsendmail($cstring)
	{

		#$sql = "select email,name,id from user_instagram_profil where (user_bio like '%agence%' or user_bio like '%france%' or user_bio like '%paris%' or user_bio like '%toulouse%' or user_bio like '%bonjour%' or email like '%.gp%' or email like '%.fr%' or email like '%clemence%' or email like '%carole%' or email like '%stephane%' or email like '%jeanne%' or email like '%guillaume%' or email like '%axelle%' or email like '%francois%' or email like '%laetitia%' or email like '%maison%' ) and (mailing is not null) and (email!='' ) and (mailing < DATE_SUB(NOW(), INTERVAL 250 day) ) and (is_unsubscribe is null) and is_good_email=1";
		$sql = "select email,name,id from user_instagram_profil where  is_good_email=1 and email!=''  and mailing is not null and is_unsubscribe is null and (mailing < DATE_SUB(NOW(), INTERVAL 180 day))";
		#echo "Requete SQL " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		return $result;

	}


	public function getinstagrowthRESENDFrenchmasterclass($cstring)
	{

		/*$sql = "select email,name,id from user_instagram_profil where (user_bio like '%agence%' or user_bio like '%france%' or user_bio like '%paris%' or user_bio like '%toulouse%' or user_bio like '%bonjour%' or email like '%.gp%' or email like '%.fr%' or email like '%clemence%' or email like '%carole%' or email like '%stephane%' or email like '%jeanne%' or email like '%guillaume%' or email like '%axelle%' or email like '%francois%' or email like '%laetitia%' or email like '%maison%' ) and (mailing is not null) and (email!='' ) and (mailing < DATE_SUB(NOW(), INTERVAL 30 day) ) and (is_unsubscribe is null) and is_good_email=1 and email !='' ";*/
		$sql = "select email,name,id from user_instagram_profil where (email like '%.fr' or email like '%.com')  and (mailing_masterclass is null and mailing < DATE_SUB(NOW(), INTERVAL 25 day) ) and (is_unsubscribe is null) and is_good_email=1 and email !=''";
		//$sql = "select email,name,id from user_instagram_profil where  is_good_email=1 and email!=''  and mailing is not null and is_unsubscribe is null and (mailing < DATE_SUB(NOW(), INTERVAL 90 day))";
		#echo "Requete SQL " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		return $result;

	}



	public function getinstagrowthResendmail($cstring)
	{

		$sql = "select email,name,id,date_create,date_update from user_instagram_profil where mailing is not null and is_unsubscribe is null and email!=''  and date_update < DATE_SUB(NOW(), INTERVAL 30 day )";
		//$sql = "select email,name,id from user_instagram_profil where  email is not null and email!=''  and mailing is null and is_unsubscribe is null";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}


	public function getinstagrowthusers($cstring)
	{
	
		$sql = "select email,name,id,user_profil_img_link from user_instagram_profil where  user_nb_followers > 4500 and user_profil_img_link!='' ";
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}

	public function getuserstripesession($cstring, $stripe_session)
	{
		$sql = "select email, name, company, instagram, id from user where stripe_session='" . $stripe_session . "'";
		echo " requete " . $sql;
		$result = mysqli_query($cstring, $sql);
		return $result;
	}

	public function getuserstripecustomer($cstring,$customer_id)
	{
		$sql = "select email, name, company, instagram, id from user where stripe_customer_id='" . $customer_id . "'";
		$result = mysqli_query($cstring, $sql);
		return $result;
	}


	public function getinstagrowthpicture($cstring)
	{
	
		$sql = "select email,name,id,user_profil_img_link from user_instagram_profil where  user_nb_followers > 4500 and user_profil_img_link!='' and file_picture is null";
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}

	public function updatecustomerstripe($cstring,$cid,$customer_id)
	{
		$sql = "update user set is_active=1,stripe_customer_id='" . $customer_id . "' where id='" . $cid . "'";
		$result = mysqli_query($cstring,$sql);
		return $result;

	}

	public function updatefilepicture($cstring,$cid,$mfilename)
	{
		$sql = "update user_instagram_profil set file_picture='" . $mfilename . "' where id='" . $cid . "'"; 
		#echo " La requete " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		return $result;
		
	}



	public function getinstagrowthcarderror($cstring)
	{
	
		$sql = "select email,user.id from user inner join user_card_info on user.id = user_card_info.user_id inner join user_abo_info on user.id=user_abo_info.user_id where user_abo_info.is_active=1 and user_card_info.customer_id=''";
		#echo "Requete SQL " . $sql;
		#exit();
		$result = mysqli_query($cstring,$sql);
		return $result;

	}


	public function retrieveabo($cstring)
	{
		$sql = "select user.id,user_card_info.customer_id,user_abo_info.montant_abo_ttc,user_abo_info.montant_abo,user_abo_info.ref_abo from user_abo_info  inner join user_card_info on user_card_info.user_id=user_abo_info.user_id inner join user on user.id = user_abo_info.user_id where user_abo_info.is_active=1 and user_abo_info.test_period_end < DATE_ADD(NOW(),INTERVAL 0 DAY)";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}



	public function getlastabo($cstring,$user_id)
	{
		$sql = "select date_end from user_abo_paiement where user_id='" . $user_id . "' and date_end >= DATE_ADD(CURRENT_DATE(),INTERVAL 1 DAY)";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row)
		{
			return $row[0];
		}
		return -1;

	}

	public function getLastInstagramSession($cstring, $username, $offset = 0)
	{
		$sql = "SELECT profile_followers, start_time, session_id
				FROM instagram_session 
				WHERE instagram_username_interact = '" . $username . "'
				ORDER BY start_time DESC 
				LIMIT 1 OFFSET " . intval($offset);
		
		// Debug output
		#echo "Executing SQL: " . $sql . "\n";
		#exit();
		
		$result = mysqli_query($cstring, $sql);
		if (!$result) {
			// Log the error for debugging
			echo "MySQL Error: " . mysqli_error($cstring) . "\n";
			return null;
		}
		
		$row = mysqli_fetch_assoc($result);
		return $row ? $row : null;
	}

	/**
	 * Logs target interaction metrics into the instagram_remove_config table if no duplicate exists.
	 *
	 * @param object $cstring    Database connection from class context
	 * @param array  $target     Target information containing:
	 *                          - target: string (Instagram username)
	 *                          - job_name: string (Type of job being performed)
	 * @param array  $targetInfo Interaction metrics containing:
	 *                          - total_interactions: float
	 *                          - total_likes: int
	 *                          - failure_rate: float
	 *                          - private_rate: float
	 * @param string $sessionId  Current session identifier
	 * @param string $username  Instagram username Interact of the target
	 *
	 * @return bool Returns true on successful operation, false otherwise
	 */
	public function logRemoveTarget($cstring, $target, $targetInfo, $sessionId, $username)
	{
		// First, check for existing entrye
		echo "Checking for existing entry for '$username'\n";
		$checkSql = "SELECT id FROM instagram_remove_config 
					 WHERE target = '" . mysqli_real_escape_string($cstring, $target['target']) . "'
					 AND job_name = '" . mysqli_real_escape_string($cstring, $target['job_name']) . "'
					 AND session_id = '" . mysqli_real_escape_string($cstring, $sessionId) . "'
					 AND instagram_username_interact = '" . mysqli_real_escape_string($cstring, $username) . "'
					 AND is_removed = FALSE
					 LIMIT 1";

		$checkResult = mysqli_query($cstring, $checkSql);
		
		if (!$checkResult) {
			error_log("MySQL Error in logRemoveTarget (check): " . mysqli_error($cstring));
			error_log("Query: " . $checkSql);
			return false;
		}

		// If entry exists, don't insert
		if (mysqli_num_rows($checkResult) > 0) {
			return true; // Entry already exists, no need to insert
		}

		// If no existing entry, proceed with insert
		$insertSql = "INSERT INTO instagram_remove_config (
					target,
					job_name,
					session_id,
					instagram_username_interact,
					total_interactions,
					total_likes,
					failure_rate,
					total_followed,
					private_rate
				) VALUES (
					'" . mysqli_real_escape_string($cstring, $target['target']) . "',
					'" . mysqli_real_escape_string($cstring, $target['job_name']) . "',
					'" . mysqli_real_escape_string($cstring, $sessionId) . "',
					'" . mysqli_real_escape_string($cstring, $username) . "',
					'" . (float)$targetInfo['total_interactions'] . "',
					'" . (int)$targetInfo['total_likes'] . "',
					'" . (float)$targetInfo['failure_rate'] . "',
					'" . (int)$targetInfo['total_followed'] . "',
					'" . (float)$targetInfo['private_rate'] . "'
				)";
		
		$insertResult = mysqli_query($cstring, $insertSql);
		
		if (!$insertResult) {
			error_log("MySQL Error in logRemoveTarget (insert): " . mysqli_error($cstring));
			error_log("Query: " . $insertSql);
			return false;
		}
		
		return true;
	}

	/**
	 * Determines whether a target should be removed based on interaction metrics and variation thresholds.
	 *
	 * This function evaluates interaction patterns to decide if a target should be removed from future
	 * interactions. It considers two main scenarios:
	 * 1. Low variation scenarios where basic interaction thresholds are evaluated
	 * 2. High failure rate scenarios where more complex conditions are assessed
	 *
	 * @param string $target         Instagram username being evaluated for removal
	 * @param array  $targetInfo     Array containing interaction metrics:
	 *                              - total_interactions: int
	 *                              - total_followed: int
	 *                              - total_likes: int
	 *                              - failure_rate: float
	 *                              - private_rate: float
	 * @param float  $variation      Current variation value
	 * @param float  $variationLimit Threshold for variation evaluation
	 *
	 * @return bool Returns true if target should be removed, false otherwise
	 *
	 * @throws InvalidArgumentException If required parameters are missing or invalid
	 */
	public function checkRemoveTarget($target, $targetInfo, $variation, $variationLimit)
	{
		// Input validation
		if (!$targetInfo || !is_array($targetInfo)) {
			throw new InvalidArgumentException('Target info must be a valid array');
		}

		// Extract metrics for better readability
		$totalInteractions = (int)($targetInfo['total_interactions'] ?? 0);
		$totalFollowed = (int)($targetInfo['total_followed'] ?? 0);
		$totalLikes = (int)($targetInfo['total_likes'] ?? 0);
		$failureRate = (float)($targetInfo['failure_rate'] ?? 0);
		$privateRate = (float)($targetInfo['private_rate'] ?? 0);

		// Case 1: Low variation scenario
		if ($variation < $variationLimit) {
			if ($totalInteractions > 25 || 
				$totalFollowed > 5 || 
				$totalLikes > 15) {
				return true;
			}
		}

		// Case 2: High failure rate scenario
		if ($totalInteractions > 55 && 
			$failureRate > 65 && 
			($privateRate > 80 || $totalFollowed < 3 || $totalLikes < 10)) {
			return true;
		}

		return false;
	}

	/**
	 * Retrieves a list of targets from instagram_add_config that are ready for processing.
	 *
	 * This function fetches targets that:
	 * - Are associated with a specific Instagram username (instagram_username_interact)
	 * - Have an is_added status of 2
	 * - Are ordered by date_created in descending order
	 *
	 * @param mysqli $cstring   Database connection object
	 * @param string $username  Instagram username performing the interaction
	 *
	 * @return array{
	 *     success: bool,
	 *     data?: array<array{
	 *         instagram_username: string,   // Instagram username of the target
	 *         target: string,              // Target Instagram username
	 *         job_name: string,            // Type of job to be performed
	 *         followers: int,              // Number of followers
	 *         following: int,              // Number of following
	 *         date_created: string         // Timestamp of when target was added
	 *     }>,
	 *     error?: string                   // Error message if success is false
	 * }
	 *
	 * @throws mysqli_sql_exception When database query fails
	 *
	 * Example usage:
	 * ```php
	 * $result = $instagrowth->retrieveAddTarget($cstring, "username");
	 * if ($result['success']) {
	 *     foreach ($result['data'] as $target) {
	 *         echo "Target: " . $target['target'] . "\n";
	 *         echo "Job: " . $target['job_name'] . "\n";
	 *     }
	 * }
	 * ```
	 */
	public function retrieveAddTarget(mysqli $cstring, string $username): array
	{
		// Input validation
		if (!$cstring || empty($username)) {
			return [
				'success' => false,
				'error' => 'Invalid input parameters'
			];
		}

		try {
			// Prepare the statement
			$stmt = $cstring->prepare("
				SELECT 
					instagram_username,
					target,
					job_name,
					followers,
					following,
					date_created
				FROM instagram_add_config 
				WHERE instagram_username_interact = ? 
				AND is_added = 1
				ORDER BY date_created DESC
			");

			if (!$stmt) {
				throw new mysqli_sql_exception("Failed to prepare statement: " . $cstring->error);
			}

			// Bind parameters
			$stmt->bind_param('s', $username);
			
			// Execute the query
			if (!$stmt->execute()) {
				throw new mysqli_sql_exception("Failed to execute query: " . $stmt->error);
			}

			// Get results
			$result = $stmt->get_result();
			$targets = [];

			while ($row = $result->fetch_assoc()) {
				$targets[] = [
					'instagram_username' => $row['instagram_username'],
					'target' => $row['target'],
					'job_name' => $row['job_name'],
					'followers' => (int)$row['followers'],
					'following' => (int)$row['following'],
					'date_created' => $row['date_created']
				];
			}

			// Clean up
			$stmt->close();

			return [
				'success' => true,
				'data' => $targets
			];

		} catch (mysqli_sql_exception $e) {
			error_log("Database error in retrieveAddTarget: " . $e->getMessage());
			
			return [
				'success' => false,
				'error' => 'Database error occurred'
			];
		}
	}

/**
 * Updates the is_added status for processed targets in instagram_add_config table.
 *
 * This function marks specific target records as processed (is_added = 0) based on the
 * combination of target username, interacting username, and job name.
 *
 * @param mysqli $cstring Database connection object
 * @param string $username Instagram username performing the interaction
 * @param string $target Target Instagram username to update
 * @param string $job_name Type of job being performed
 * @return bool True if update was successful, False otherwise
 */
public function updateAddConfigTargetList(mysqli $cstring, string $username, string $target, string $job_name): bool 
{
    try {
        // Input validation
        if (empty($username) || empty($target) || empty($job_name)) {
            error_log("Error: Missing required parameters in updateAddConfigTargetList");
            return false;
        }

		// Build the SQL query for debugging
        $sql = "UPDATE instagram_add_config 
                SET is_added = 42,
                    date_updated = NOW()
                WHERE instagram_username = '" . $target . "'
                AND instagram_username_interact = '" . $username . "'
                AND job_name = '" . $job_name . "'
                AND is_added ='1'";
                
        // Log the SQL query for debugging
        error_log("Executing SQL query: " . $sql);


        // Prepare the update statement
        $stmt = $cstring->prepare("
            UPDATE instagram_add_config 
            SET is_added = 42,
                date_updated = NOW()
            WHERE instagram_username = ?
            AND instagram_username_interact = ?
            AND job_name = ?
            AND is_added = 1
        ");

        if (!$stmt) {
            error_log("Error preparing statement: " . $cstring->error);
            return false;
        }

        // Bind parameters
        $stmt->bind_param('sss', $target, $username, $job_name);
		#$stmt->bind_param('sss', $username, $target, $job_name);
        
        // Execute the update
        $success = $stmt->execute();
        
        if (!$success) {
            error_log("Error executing update: " . $stmt->error);
            $stmt->close();
            return false;
        }

        // Get number of affected rows
        $affected_rows = $stmt->affected_rows;
        
        // Log the result
        error_log(sprintf(
            "Updated %d records for target: %s, username: %s, job: %s",
            $affected_rows,
            $target,
            $username,
            $job_name
        ));

        // Close the statement
        $stmt->close();

        // Return true if at least one record was updated
        return $affected_rows > 0;

		} catch (mysqli_sql_exception $e) {
			error_log("Database error in updateAddConfigTargetList: " . $e->getMessage());
			return false;
		} catch (Exception $e) {
			error_log("Unexpected error in updateAddConfigTargetList: " . $e->getMessage());
			return false;
		}
	}

	/**
 * Updates the is_removed status for processed targets in instagram_remove_config table.
 *
 * This function marks specific target records as removed (is_removed = 1) based on the
 * combination of target username, interacting username, and job name.
 *
 * @param mysqli $cstring Database connection object
 * @param string $username Instagram username performing the interaction
 * @param string $target Target Instagram username to be marked as removed
 * @param string $job_name Type of job being performed
 * @return bool True if update was successful, False otherwise
 * @throws mysqli_sql_exception If database query fails
 */
public function updateRemoveConfigAddTargetList(mysqli $cstring, string $username, string $target, string $job_name): bool 
{
    try {
        // Input validation
        if (empty($username) || empty($target) || empty($job_name)) {
            error_log("Error: Missing required parameters in updateRemoveConfigAddTargetList");
            return false;
        }

        // Prepare the update statement
        $stmt = $cstring->prepare("
            UPDATE instagram_remove_config 
            SET is_removed = 42,
                date_updated = NOW()
            WHERE target = ?
            AND instagram_username_interact = ?
            AND job_name = ?
            AND is_removed = 0
        ");

        if (!$stmt) {
            error_log("Error preparing statement: " . $cstring->error);
            return false;
        }

        // Bind parameters
        $stmt->bind_param('sss', $target, $username, $job_name);
        
        // Execute the update
        $success = $stmt->execute();
        
        if (!$success) {
            error_log("Error executing update: " . $stmt->error);
            $stmt->close();
            return false;
        }

        // Get number of affected rows
        $affected_rows = $stmt->affected_rows;
        
        // Log the result
        error_log(sprintf(
            "Updated %d records for target: %s, username: %s, job: %s",
            $affected_rows,
            $target,
            $username,
            $job_name
        ));

        // Close the statement
        $stmt->close();

        // Return true if at least one record was updated
        return $affected_rows > 0;

		} catch (mysqli_sql_exception $e) {
			error_log("Database error in updateRemoveConfigAddTargetList: " . $e->getMessage());
			return false;
		} catch (Exception $e) {
			error_log("Unexpected error in updateRemoveConfigAddTargetList: " . $e->getMessage());
			return false;
			}
	}

	/**
	 * Retrieves a list of pending targets for removal from the instagram_remove_config table.
	 *
	 * This function fetches targets that:
	 * - Haven't been marked as removed yet (is_removed = 0)
	 * - Are associated with a specific Instagram username
	 * 
	 * @param mysqli $cstring   Database connection object
	 * @param string $username  Instagram username performing the interaction
	 * 
	 * @return array{
	 *     success: bool,
	 *     data?: array<array{
	 *         target: string,              // Instagram username of the target
	 *         job_name: string,            // Type of job (e.g., 'follow', 'like', etc.)
	 *         total_interactions: int,      // Total number of interactions with this target
	 *         failure_rate: float,         // Percentage of failed interactions
	 *         private_rate: float,         // Percentage of private account encounters
	 *         session_id: string,          // Unique identifier for the interaction session
	 *         created_at: string           // Timestamp when the target was added
	 *     }>,
	 *     error?: string                   // Error message if success is false
	 * }
	 * 
	 * @throws mysqli_sql_exception When database query fails
	 * 
	 * Example usage:
	 * ```php
	 * $result = $instagrowth->retrieveRemoveTarget($cstring, "username");
	 * if ($result['success']) {
	 *     foreach ($result['data'] as $target) {
	 *         echo "Target: " . $target['target'] . "\n";
	 *         echo "Job: " . $target['job_name'] . "\n";
	 *         echo "Interactions: " . $target['total_interactions'] . "\n";
	 *     }
	 * }
	 * ```
	 */
	public function retrieveRemoveTarget(mysqli $cstring, string $username): array 
	{
		// Input validation
		if (!$cstring || empty($username)) {
			return [
				'success' => false,
				'error' => 'Invalid input parameters'
			];
		}

		try {
			// Prepare the statement
			$stmt = $cstring->prepare("
				SELECT 
					target,
					job_name,
					total_interactions,
					failure_rate,
					private_rate,
					session_id,
					date_created
				FROM instagram_remove_config 
				WHERE instagram_username_interact = ? 
				AND is_removed = 0
				ORDER BY date_created DESC
			");

			if (!$stmt) {
				throw new mysqli_sql_exception("Failed to prepare statement: " . $cstring->error);
			}

			// Bind parameters
			$stmt->bind_param('s', $username);
			
			// Execute the query
			if (!$stmt->execute()) {
				throw new mysqli_sql_exception("Failed to execute query: " . $stmt->error);
			}

			// Get results
			$result = $stmt->get_result();
			$targets = [];

			while ($row = $result->fetch_assoc()) {
				$targets[] = [
					'target' => $row['target'],
					'job_name' => $row['job_name'],
					'total_interactions' => (int)$row['total_interactions'],
					'failure_rate' => (float)$row['failure_rate'],
					'private_rate' => (float)$row['private_rate'],
					'session_id' => $row['session_id'],
					'created_at' => $row['date_created']
				];
			}

			// Clean up
			$stmt->close();

			return [
				'success' => true,
				'data' => $targets
			];

		} catch (mysqli_sql_exception $e) {
			error_log("Database error in retrieveRemoveTarget: " . $e->getMessage());
			
			return [
				'success' => false,
				'error' => 'Database error occurred'
			];
		}
	}

	/**
	 * Saves target information to instagram_add_config table if not already exists.
	 *
	 * @param object $cstring   Database connection from class context
	 * @param string $username  Instagram username doing the interaction
	 * @param array  $target    Target information containing:
	 *                         - instagram_username: string
	 *                         - followers: int
	 *                         - following: int
	 *                         - job_name: string
	 *                         - target: string
	 *
	 * @return int Returns 1 on successful insertion, 0 otherwise
	 */
	public function setToValidateTargetList($cstring, $username, $target)
	{
		// First, check for existing entry
		$checkSql = "SELECT id FROM instagram_add_config 
					 WHERE instagram_username = '" . mysqli_real_escape_string($cstring, $target['instagram_username']) . "'
					 AND instagram_username_interact = '" . mysqli_real_escape_string($cstring, $username) . "'
					 LIMIT 1";

		$checkResult = mysqli_query($cstring, $checkSql);
		
		if (!$checkResult) {
			error_log("MySQL Error in setToValidateTargetList (check): " . mysqli_error($cstring));
			return 0;
		}

		// If entry exists, don't insert
		if (mysqli_num_rows($checkResult) > 0) {
			return 0;
		}

		// Insert with exact field mappings as specified
		$insertSql = "INSERT INTO instagram_add_config SET 
						followers = " . (int)$target['followers'] . ",
						target = '" . mysqli_real_escape_string($cstring, $target['target']) . "',
						job_name = '" . mysqli_real_escape_string($cstring, $target['job_name']) . "',
						instagram_username = '" . mysqli_real_escape_string($cstring, $target['instagram_username']) . "',
						instagram_username_interact = '" . mysqli_real_escape_string($cstring, $username) . "',
						following = " . (int)$target['following'];
		
		$insertResult = mysqli_query($cstring, $insertSql);
		
		if (!$insertResult) {
			error_log("MySQL Error in setToValidateTargetList (insert): " . mysqli_error($cstring));
			return 0;
		}
		
		return 1;
	}

	/**
	 * Retrieves a list of potential target Instagram usernames with detailed metrics.
	 * Excludes usernames that are already in the add_config table.
	 *
	 * This function identifies valuable target accounts by analyzing:
	 * - Potency ratio (engagement potential)
	 * - Follower count (to target appropriate account sizes)
	 * - Previous interaction history (to avoid duplicates)
	 * - Recent activity (prioritizing newer interactions)
	 * - Existing configurations (to avoid duplicates)
	 *
	 * @param object $cstring        Database connection from class context
	 * @param string $username       Instagram username doing the interaction
	 * @param float  $potency_ratio  Minimum potency ratio threshold
	 *
	 * @return array|null Returns array of targets or null if query fails
	 *                    Each array element contains:
	 *                    - instagram_username: string (target account)
	 *                    - followers: int (number of followers)
	 *                    - job_name: string (type of job)
	 *                    - target: string (target username)
	 *                    - liked: int (like status)
	 *                    - watched: int (watch status)
	 *                    - following: int (follow status)
	 *                    - mutual_friends: int (number of mutual friends)
	 *                    - biography: string (user bio)
	 *                    - potency_ratio: float (engagement ratio)
	 *                    - last_interaction: datetime (last interaction time)
	 */
	public function getTargetList($cstring, $username, $potency_ratio)
	{
		// Sanitize inputs
		$escaped_username = mysqli_real_escape_string($cstring, $username);
		$potency_ratio = (float)$potency_ratio;

		$sql = "SELECT DISTINCT 
					i1.instagram_username,
					i1.followers,
					i1.job_name,
					i1.target,
					i1.liked,
					i1.watched,
					i1.following,
					i1.mutual_friends,
					i1.biography,
					i1.potency_ratio,
					i1.last_interaction
				FROM instagram_interact i1
				WHERE i1.instagram_username_interact = '$escaped_username'
				AND i1.potency_ratio > $potency_ratio
				AND i1.skip_reason = 'POTENCY_RATIO'
				AND i1.followers BETWEEN 600 AND 12000
				AND i1.posts_count > 20
				AND NOT EXISTS (
					SELECT 1 
					FROM instagram_interact i2 
					WHERE i2.target = i1.instagram_username
					 AND i2.instagram_username_interact = '$escaped_username'
				)
				AND NOT EXISTS (
					SELECT 1 
					FROM instagram_add_config iac 
					WHERE iac.instagram_username = i1.instagram_username
					 AND iac.instagram_username_interact = '$escaped_username'
				)
				ORDER BY i1.last_interaction DESC
				LIMIT 300";

		$result = mysqli_query($cstring, $sql);
		
		if (!$result) {
			error_log("MySQL Error in getTargetList: " . mysqli_error($cstring));
			error_log("Query: " . $sql);
			return null;
		}

		$targets = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$targets[] = [
				'instagram_username' => $row['instagram_username'],
				'followers' => (int)$row['followers'],
				'job_name' => $row['job_name'],
				'target' => $row['target'],
				'liked' => (int)$row['liked'],
				'watched' => (int)$row['watched'],
				'following' => (int)$row['following'],
				'mutual_friends' => (int)$row['mutual_friends'],
				'biography' => $row['biography'],
				'potency_ratio' => (float)$row['potency_ratio'],
				'last_interaction' => $row['last_interaction']
			];
		}

		mysqli_free_result($result);
		return $targets;
	}

	/**
	 * Builds a comprehensive analysis of target interactions within a specific session and job context
	 *
	 * @param mysqli $cstring       Database connection object
	 * @param string $target        Instagram username being targeted
	 * @param string $session_id    Unique identifier for the interaction session
	 * @param string $job_name      Type of job being performed
	 * 
	 * @return array|null Returns an array containing interaction metrics or null if error occurs
	 *                    Array structure:
	 *                    [
	 *                        'total_followed' => int,      // Total successful follows
	 *                        'total_likes' => int,         // Total successful likes
	 *                        'total_unfollowed' => int,    // Total successful unfollows
	 *                        'total_watched' => int,       // Total successful story/video watches
	 *                        'total_interactions' => int,   // Total interaction attempts
	 *                        'failure_rate' => float,      // Percentage of failed interactions
	 *                        'private_rate' => float       // Percentage of failures due to private accounts
	 *                    ]
	 * 
	 * @throws mysqli_sql_exception If SQL query fails
	 */
	public function buildTargetInfo($cstring, $target, $session_id, $job_name)
	{
		// Input validation
		if (!$cstring || !$target || !$session_id || !$job_name) {
			error_log("buildTargetInfo: Missing required parameters");
			return null;
		}

		// Escape inputs to prevent SQL injection
		$escaped_target = mysqli_real_escape_string($cstring, $target);
		$escaped_session = mysqli_real_escape_string($cstring, $session_id);
		$escaped_job = mysqli_real_escape_string($cstring, $job_name);

		// Updated query to include new metrics
		$sql = "SELECT 
					COUNT(*) as total_interactions,
					SUM(CASE WHEN followed = 1 THEN 1 ELSE 0 END) as total_followed,
					SUM(CASE WHEN liked = 1 THEN 1 ELSE 0 END) as total_likes,
					SUM(CASE WHEN unfollowed = 1 THEN 1 ELSE 0 END) as total_unfollowed,
					SUM(CASE WHEN watched = 1 THEN 1 ELSE 0 END) as total_watched,
					SUM(CASE WHEN skip_reason IS NOT NULL THEN 1 ELSE 0 END) as total_failures,
					SUM(CASE WHEN skip_reason = 'IS_PRIVATE' THEN 1 ELSE 0 END) as private_failures
				FROM instagram_interact
				WHERE target = '" . $escaped_target . "'
				AND session_id = '" . $escaped_session . "'
				AND job_name = '" . $escaped_job . "'";

		// Execute query with error handling
		$result = mysqli_query($cstring, $sql);
		if (!$result) {
			error_log("buildTargetInfo SQL Error: " . mysqli_error($cstring));
			return null;
		}

		$row = mysqli_fetch_assoc($result);
		if (!$row) {
			mysqli_free_result($result);
			return null;
		}

		// Calculate metrics
		$total_interactions = (int)$row['total_interactions'];
		$total_failures = (int)$row['total_failures'];
		
		// Prepare return array with computed metrics
		$metrics = [
			'total_followed' => (int)$row['total_followed'],
			'total_likes' => (int)$row['total_likes'],
			'total_unfollowed' => (int)$row['total_unfollowed'],
			'total_watched' => (int)$row['total_watched'],
			'total_interactions' => $total_interactions,
			'failure_rate' => $total_interactions > 0 
				? round(($total_failures / $total_interactions) * 100, 2) 
				: 0,
			'private_rate' => $total_failures > 0 
				? round(($row['private_failures'] / $total_failures) * 100, 2) 
				: 0
		];

		mysqli_free_result($result);
		return $metrics;
	}

	public function getListTargetJob($cstring, $session_id)
	{
		if (!$cstring || !$session_id) {
			error_log("Invalid connection or session_id");
			return null;
		}

		$escaped_session_id = mysqli_real_escape_string($cstring, $session_id);
		
		// Updated query to use instagram_interact table
		$sql = "SELECT target, job_name 
				FROM instagram_interact 
				WHERE session_id = '" . $escaped_session_id . "' group by target, job_name";
		
		$result = mysqli_query($cstring, $sql);
		if (!$result) {
			error_log("MySQL Error: " . mysqli_error($cstring));
			return null;
		}
		
		$targets = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$targets[] = $row;
		}
		
		mysqli_free_result($result);
		return $targets;
	}

	public function evalGainFollowers($cstring, $username)
	{
		// Get current session data
		$currentSession = $this->getLastInstagramSession($cstring, $username, 0);
		// Get previous session data
		$previousSession = $this->getLastInstagramSession($cstring, $username, 1);
		
		if ($currentSession && $previousSession) {
			return array(
				'current' => $currentSession['profile_followers'],
				'previous' => $previousSession['profile_followers'],
				'current_time' => $currentSession['start_time'],
				'previous_time' => $previousSession['start_time'],
				'current_session_id' => $currentSession['session_id'],
				'previous_session_id' => $previousSession['session_id']
			);
		}
		
		return -1;
	}

	


}

?>
