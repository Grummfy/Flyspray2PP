<?php

interface IModules
{
	/**
	 * Converti de X vers PP
	 */
	public function convert();
	
	/**
	 * Renvoi les nouveau ids des enregistrement précédement converti
	 */
	public function getNewIds();
}

# EOF
