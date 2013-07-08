<?php
	/**
	*
	* @package com.Itschi.Index
	* @since 2013/02/13
	*
	*/
	
	if (!file_exists('config.php')) {
		header('Location: install.php?step=1');
		exit;
	}
	
	require 'base.php';
	include 'lib/feed.php';
	include 'lib/news/NewsHelper.php';
	include 'lib/news/NewsArticle.php';

	\Itschi\lib\feed(5);

	if ($config['index_news']) {
		$articlesArr = \Itschi\lib\News\NewsHelper::getArticles((int)$_GET['page']);

		$articles = $articlesArr['articles'];
		$pages_num = $articlesArr['pagesNum'];

		if (count($articles) > 0) {
			foreach ($articles as $a) {
				template::assignBlock('news', array(
					'TITLE'	=>	$a->getTitle(),
					'TEXT'	=>	replace($a->getText(), $a->BBCodesEnabled(), $a->smiliesEnabled(), $a->URLsEnabled()),
					'DATE'	=>	$a->getFormattedDate(),
					'TOPIC_ID'	=>	$a->getTopicID(),
					'FORUM_ID'	=>	$a->getForumID(),
					'FORUM_TITLE'	=>	$a->getForumTitle(),
					'COMMENTS_NUM'	=>	$a->getCommentsNum()
				));
			}
		}
	}

	template::assign(array(
		'TITLE_TAG'	=>	'Startseite | ',
		'USER_LEGEND'	=>	$user->legend($user->row['user_level']),
		'NEWS_ACTIVE'	=>	$config['index_news'],
		'PAGENR'		=>	max(1, (int)$_GET['page']),
		'PAGES_NUM'		=>	$pages_num,
		'PAGES'			=>	($pages_num > 1) ? pages($pages_num, max(1, (int)$_GET['page']), 'index.php?page=') : ''
	));

	template::display('index');
?>
