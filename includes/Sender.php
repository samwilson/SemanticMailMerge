<?php
/**
 * This file is part of the MediaWiki extension 'SemanticMailMerge'.
 *
 * SemanticMailMerge is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SemanticMailMerge is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SemanticMailMerge.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @file
 */
/**
 * Mail sending class.
 * Called from /extensions/SemanticMailMerge/maintenance/send.php
 */
class SemanticMailMerge_Sender extends Maintenance {

	/** @var Title */
	private $title;

	/**
	 * 
	 */
	public function __construct() {
		parent::__construct();
		$this->addOption('title', 'Title of a page containing a mailmerge query.', true, true, 't');
	}

	/**
	 * Send all emails for the specified page title.
	 *
	 * @return boolean true
	 */
	public function execute() {
		$this->title = Title::newFromText($this->getOption('title'));
		$emails = new SemanticMailMerge_ORM();
		$emailsResult = $emails->select(null, array('title' => "$this->title"));
		foreach ($emailsResult as $emailResult) {
			$email = $this->prepareTemplate($emailResult);
			$this->sendMail($email['recipients'], $email['message']);
		}
		return true;
	}

	/**
	 * Get HTML email message and list of email recipients from given email
	 * info.
	 *
	 * @param ORMRow $emailInfo
	 * @return array With 'message' and 'recipients' items.
	 */
	protected function prepareTemplate($emailInfo) {
		$template = $emailInfo->getField('template');
		$params = $emailInfo->getField('params');
		$fauxreq = new FauxRequest(array(
			'action' => 'parse',
			'text' => $this->getTemplate($template, $params),
			'contentmodel'=>'wikitext',
		));
		$api = new ApiMain($fauxreq);
		$api->execute();
		$data = $api->getResultData();
		$message = $data['parse']['text']['*'];

		$recipients = array();
		foreach ($params['To'] as $to) {
			$recipients[] = new MailAddress($to);
		}

		return array('message'=>$message, 'recipients'=>$recipients);
	}

	/**
	 * Get the template wikitext, populated with parameters. Parameter with
	 * multiple values will have their values joined with commas (for separation
	 * with e.g. the {{#arraymap:}} parser function from SemanticForms).
	 *
	 * @param string $name
	 * @param array $params
	 * @return string
	 */
	protected function getTemplate($name, $params) {
		$template_params = '';
		foreach ($params as $key=>$val) {
			if (is_array($val)) {
				$val = join (',', $val);
			}
			$template_params .= "|?$key=$val";
		}
		return '{{'.$name.$template_params.'}}';
	}

	/**
	 * Send an email to one or more recipients.
	 * Outputs an error if email does not send.
	 *
	 * @uses UserMailer::send() To actually send the mail.
	 * @global string $wgPasswordSender
	 */
	protected function sendMail($recipients, $message) {
		global $wgPasswordSender;
		$from = new MailAddress( $wgPasswordSender );
		$subject = "$this->title";
		$status = UserMailer::send( $recipients, $from, $subject, $message, $from, 'text/html; charset=UTF-8' );
		if ( ! $status->isGood()) {
			$this->error( $status->getWikiText() );
		}
	}

}