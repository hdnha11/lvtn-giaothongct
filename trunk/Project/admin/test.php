<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
require_once dirname(__FILE__) . '/../lib/AccessControl.php';

$ac = new AccessControl('1');
echo 'Perm key of id=1 is ', $ac->getPermKeyFromID(1), '<br /><br />';
echo 'Perm name of id=1 is ', $ac->getPermNameFromID(1), '<br /><br />';
echo 'Role name of id=1 is ', $ac->getRoleNameFromID(1), '<br /><br />';
echo 'Role id of user is '; print_r($ac->getUserRoles()); echo '<br /><br />';
echo 'All role id is '; print_r($ac->getAllRoles('ids')); echo '<br /><br />';
echo 'All role is '; print_r($ac->getAllRoles('full')); echo '<br /><br />';
echo 'All permissions id is '; print_r($ac->getAllPerms('ids')); echo '<br /><br />';
echo 'All permissions is '; print_r($ac->getAllPerms('full')); echo '<br /><br />';
echo 'Permissions of role id=1 is '; print_r($ac->getRolePerms(1)); echo '<br /><br />';
echo 'Permissions of role id=1 and id=2 is '; print_r($ac->getRolePerms(array(1, 2))); echo '<br /><br />';
echo 'Permissions of user id=3 is '; print_r($ac->getUserPerms(3)); echo '<br /><br />';
echo 'User id=1 has role id=1 is '; echo $ac->userHasRole(1) ? 'true' : 'false'; echo '<br /><br />';
// TODO: check hasPermission function (Fixed: postgresql boolean type show when select is t and f)
echo 'User id=1 has permission key=cap_nhat_du_lieu  is '; echo $ac->hasPermission('cap_nhat_du_lieu') ? 'true' : 'false'; echo '<br /><br />';
echo 'Get username of id=1 is ', $ac->getUsername(1), '<br /><br />';

//$roles = array(floatval(1));
//echo implode(",", $roles);

?>
</body>
</html>