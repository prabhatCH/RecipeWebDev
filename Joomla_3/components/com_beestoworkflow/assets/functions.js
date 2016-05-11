function WorkflowProjectComment () {
	var comment = document.getElementById('beestoworkflow-comment-add');
	if (comment.style.display == 'block') {
		comment.style.display = 'none';
	} else {
		comment.style.display = 'block';
	}
}

function WorkflowProjectFile () {
	var comment = document.getElementById('beestoworkflow-file-add');
	if (comment.style.display == 'block') {
		comment.style.display = 'none';
	} else {
		comment.style.display = 'block';
	}
}

function uncheckThis ( id ) {
	document.getElementById(id).checked = false;
}
