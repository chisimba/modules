<?php

$html = '<h1 style="text-align:center">Moderate Unapproved Comments</h1>';
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
    $html .= '</div>';
}
echo $html;
