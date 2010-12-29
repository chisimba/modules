<?php

$html = '<h1 style="text-align:center">'.$this->objLanguage->languageText('mod_blogcomments_moderate_unapproved_comments', 'blogcomments').'</h1>';
$html .= '<form method="post">';

foreach ($comments as $comment) {
    $html .= '<div style="margin-top:20px;border-top:2px solid black">';
    $html .= '<ul>';
    $html .= '<li>Post: <a href="'.$comment['link'].'">'.htmlspecialchars($comment['post']['post_title']).'</a></li>';
    $html .= '<li>Author: '.htmlspecialchars($comment['comment_author']).'</li>';
    $html .= '<li>Email: '.htmlspecialchars($comment['comment_author_email']).'</li>';
    $html .= '<li>URL: '.htmlspecialchars($comment['comment_author_url']).'</li>';
    $html .= '<li>Date: '.date('Y-m-d H:i:s', $comment['comment_date']).'</li>';
    $html .= '<li>IP: '.htmlspecialchars($comment['comment_author_ip']).'</li>';
    $html .= '<li>User Agent: '.htmlspecialchars($comment['comment_agent']).'</li>';
    $html .= '</ul>';
    $html .= $comment['comment_content'];
    $html .= '<p><input name="comment['.$comment['id'].']" type="radio" value="approve" /> Approve';
    $html .= '<input name="comment['.$comment['id'].']" type="radio" value="delete" /> Delete';
    $html .= '<input name="comment['.$comment['id'].']" type="radio" value="" checked /> No Action</p>';
    $html .= '</div>';
}

$html .= '<p style="margin-top:20px;padding-top:20px;border-top:2px solid black"><input type="submit" value="Moderate" /></p></form>';

echo $html;
