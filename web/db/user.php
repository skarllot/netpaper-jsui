<?php

class User
{
	private $connection;

	function __construct(Connection $conn) {
		$this->connection = $conn;
	}

	function createFirstLogin($user, $password, $email, $name) {
		if ($this->getUsersCount() > 0)
			return False;

		$password = $this->saltPassword($user, $password);
		$query = "INSERT INTO user (user, password, email, name, admin, is_ldap)
			VALUES ('%s', '%s', '%s', '%s', 1, 0)";
		$count = $this->connection->query_write($query,
			array($user, $password, $email, $name));
		if ($count != 1)
			return False;

		return True;
	}

	private function getUsersCount() {
		$query = "SELECT count(id) AS count FROM user";
		$result = $this->connection->query($query, array());
		if (mysql_num_rows($result) != 1)
			return -1;

		$val = mysql_fetch_assoc($result)["count"];
		$this->connection->freeQuery($result);
		return $val;
	}

	function isAdmin() {
		return (isset($_SESSION['admin']) &&
			((bool)$_SESSION['admin']));
	}

	function isEmpty() {
		return ($this->getUsersCount() == 0);
	}

	private function isLdap($user) {
		$query = "SELECT is_ldap FROM user WHERE user = '%s'";
		$result = $this->connection->query($query, array($user));
		if (mysql_num_rows($result) != 1)
			return False;

		$val = mysql_fetch_assoc($result)["is_ldap"];
		$this->connection->freeQuery($result);
		return ((bool)$val);
	}

	function logon($user, $password) {
		$_SESSION['admin'] = False;
		$_SESSION['user'] = NULL;

		if ($this->isLdap($user))
			return $this->logonLdap($user, $password);
		else
			return $this->logonLocal($user, $password);
	}

	private function logonLocal($user, $password) {
		$password = $this->saltPassword($user, $password);
		$query = "SELECT admin FROM user WHERE user = '%s' AND password = '%s'";
		$result = $this->connection->query($query, array($user, $password));
		if (!$result)
			die('Error querying database');
		if (mysql_num_rows($result) != 1)
			return False;

		$val = mysql_fetch_assoc($result)["admin"];
		$this->connection->freeQuery($result);

		if ((bool)$val)
			$_SESSION['admin'] = True;
		$_SESSION['user'] = $user;
		return True;
	}

	private function logonLdap($user, $password) {
	}

	private function saltPassword($user, $password) {
		return hash('sha256',
			sha1(strval(strlen($user))).$user.
			sha1(strval(strlen($password))).$password.
			hash('sha256', $user)
		);
	}
}

/*
vim: ts=4 sw=4
*/
?>
