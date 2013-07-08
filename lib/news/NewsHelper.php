<?php
	/**
	*
	* @package com.Itschi.news.NewsHelper
	* @since 2013/06/22
	*
	*/

	namespace Itschi\lib\News;

	abstract class NewsHelper {
		public static function getArticles($currentPage = 1) {
			global $config, $db;

			$cRes = $db->query("
				SELECT p.*, t.topic_title, u.username, t.topic_time, f.forum_name AS forum_title
				FROM " . POSTS_TABLE . " AS p
				INNER JOIN " . TOPICS_TABLE . " AS t
					ON t.topic_id = p.topic_id
				INNER JOIN " . FORUMS_TABLE . " AS f
					ON t.forum_id = f.forum_id
				INNER JOIN " . USERS_TABLE . " AS u
					ON u.user_id = p.user_id
				WHERE p.is_topic = 1 AND f.is_news = 1
				ORDER BY p.post_id DESC
			");

			$page = (isset($currentPage)) ? max($currentPage, 1) : 1;
			$pages_num = ceil($db->num_rows($cRes) / $config['posts_perpage']);

			$res = $db->query("
				SELECT p.*, t.topic_title, u.username, t.topic_time, f.forum_name AS forum_title
				FROM " . POSTS_TABLE . " AS p
				INNER JOIN " . TOPICS_TABLE . " AS t
					ON t.topic_id = p.topic_id
				INNER JOIN " . FORUMS_TABLE . " AS f
					ON t.forum_id = f.forum_id
				INNER JOIN " . USERS_TABLE . " AS u
					ON u.user_id = p.user_id
				WHERE p.is_topic = 1 AND f.is_news = 1
				ORDER BY p.post_id DESC
				LIMIT " . ($page * $config['posts_perpage'] - $config['posts_perpage']) . ", " . $config['posts_perpage'] . "
			");

			$articles = array();

			while ($row = $db->fetch_object($res)) {
				$article = new NewsArticle(
					$row->forum_id,
					$row->topic_id,
					$row->topic_title,
					$row->post_text,
					$row->forum_title,
					$row->user_id,
					$row->username,
					$row->topic_time
				);

				$article->setOptions(array(
					'BBCodes'	=>	$row->enable_bbcodes,
					'smilies'	=>	$row->enable_smilies,
					'URLs'		=>	$row->enable_urls
				));

				$articles[] = $article;
			}

			return array(
				'articles'	=>	$articles,
				'pagesNum'	=>	$pages_num
			);
		}
	}
?>