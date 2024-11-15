<?php 

declare(strict_types=1);


?>



<?php
function getTimeAgo($date)
{
    $currentTime = time();

    $difference = $currentTime - strtotime($date);

    $differenceInSeconds = floor($difference);
    $differenceInMinutes = floor($differenceInSeconds / 60);
    $differenceInHours = floor($differenceInMinutes / 60);
    $differenceInDays = floor($differenceInHours / 24);

    $timeago = '';
    if ($differenceInDays > 0) {
        $timeago = $differenceInDays . ' days ago';
    } else if ($differenceInHours > 0) {
        $timeago = $differenceInHours . ' hours ago';
    } else if ($differenceInMinutes > 1) {
        $timeago = $differenceInMinutes . ' minutes ago';
    } else if ($differenceInMinutes = 1) {
        $timeago = $differenceInMinutes . ' minute ago';
    } else {
        $timeago = '< 1 minute ago';
    }

    return $timeago;
}
?>
