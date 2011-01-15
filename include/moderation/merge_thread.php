<?php 

if (!defined('PHORUM') || phorum_page !== 'moderation') return;

$template = "merge_form";

$PHORUM['DATA']["FORM"]["forum_id"]  = $PHORUM["forum_id"];
$PHORUM['DATA']["FORM"]["thread_id"] = $msgthd_id;
$PHORUM['DATA']["FORM"]["mod_step"]  = PHORUM_DO_THREAD_MERGE;
$PHORUM['DATA']['URL']["ACTION"]     =
    phorum_api_url(PHORUM_MODERATION_ACTION_URL);

// The moderator selects the target thread to merge to.
$merge_t1   = phorum_api_user_get_setting('merge_t1');
$merge_time = phorum_api_user_get_setting('merge_t1_time');
if (
    !$merge_t1               ||
    !$merge_time             ||
     $merge_t1 == $msgthd_id ||
     $merge_time < (time() - PHORUM_MODERATE_MERGE_TIME)
) {
    // Save moderation info temporarily in the user's settings data.
    phorum_api_user_save_settings(array(
        'merge_t1'      => $msgthd_id,
        'merge_t1_time' => time()
    ));

    $PHORUM['DATA']['FORM']['merge_none'] = TRUE;

    $message = phorum_db_get_message($msgthd_id, 'message_id', TRUE);
    $PHORUM['DATA']['FORM']['merge_subject1'] = htmlspecialchars(
        $message['subject'], ENT_COMPAT, $PHORUM['DATA']['HCHARSET']);
}
// The moderator selects the source thread to merge from.
else
{
    $PHORUM['DATA']['FORM']['merge_t1'] = $merge_t1;

    $message = phorum_db_get_message($merge_t1, 'message_id', true);
    $PHORUM['DATA']['FORM']['merge_subject1'] = htmlspecialchars(
        $message['subject'], ENT_COMPAT, $PHORUM['DATA']['HCHARSET']);

    $message = phorum_db_get_message($msgthd_id);
    $PHORUM['DATA']['FORM']['thread_subject'] = htmlspecialchars(
        $message['subject'], ENT_COMPAT, $PHORUM['DATA']['HCHARSET']);
}

?>
