/*
 * Codewriter support Javascript
 * 
 * Copyright (C) 2008 Derek Keats <dkeats@uwc.ac.za>
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the 
 * GNU Lesser General Public License as published by the Free Software Foundation.
 * 
 * Read the full licence: http://www.opensource.org/licenses/lgpl-license.php
 */
function saveCode()
{
	myEditorForm.cwEditor.toggleEditor();
	myEditorForm.form.submit();
}