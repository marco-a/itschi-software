<?php
	/**
	*
	* @package com.Itschi.news.NewsArticle
	* @since 2013/07/08
	*
	*/

	namespace Itschi\lib\News;

	class NewsArticle {
		protected $forumID;
		protected $topicID;
		protected $title;
		protected $text;
		protected $forumTitle;
		protected $userID;
		protected $username;
		protected $date;
		protected $options = array();

		public function __construct($forumID, $topicID, $title, $text, $forumTitle, $userID, $username, $date) {
			$this->forumID = $forumID;
			$this->topicID = $topicID;
			$this->title = $title;
			$this->text = $text;
			$this->forumTitle = $forumTitle;
			$this->userID = $userID;
			$this->username = $username;
			$this->date = $date;
		}

		public function setOptions($options) {
			$this->options = $options;
		}

		public function getForumID() {
			return $this->forumID;
		}

		public function getTopicID() {
			return $this->topicID;
		}

		public function getTitle() {
			return $this->title;
		}

		public function getText() {
			return $this->text;
		}

		public function getForumTitle() {
			return $this->forumTitle;
		}

		public function getUserID() {
			return $this->userID;
		}

		public function getUsername() {
			return $this->username;
		}

		public function getDate() {
			return $this->date;
		}

		public function getFormattedDate() {
			return \functions::date()->strTimeDifference(date("d.m.Y H:i", $this->date), date("d.m.Y H:i"), false);
		}

		public function getCommentsNum() {
			if (!isset($this->commentsNum)) {
				global $db;

				$this->commentsNum = $db->num_rows($db->query("SELECT post_id FROM " . POSTS_TABLE . " WHERE topic_id = '".$this->topicID."'"), 0) - 1;
			}
			
			return $this->commentsNum;
		}

		public function BBCodesEnabled() {
			return ($this->options['BBCodes'] == 1);
		}

		public function smiliesEnabled() {
			return ($this->options['smilies'] == 1);
		}

		public function URLsEnabled() {
			return ($this->options['URLs'] == 1);
		}
	}
?>