<?php

namespace App\Service\Util\Console;

use App\Service\Util\Console\Model\Message;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class Console
 * @package App\Service\Util\Console
 *
 * @author Jean Marius <jean.marius@dyosis.com>
 */
class Console
{
	protected static $messageIcons = array(
		Message::TYPE_SUCCESS => 'fas fa-check',
		Message::TYPE_INFO => 'fas fa-info-circle',
		Message::TYPE_WARNING => 'fas fa-exclamation-triangle',
		Message::TYPE_DANGER => 'fas fa-times',
	);

	/**
	 * @var Session
	 */
	protected $session;

	/**
	 * @var array
	 */
	protected $messages;

	/**
	 * @var int
	 */
	protected $index;

	/**
	 * Console constructor.
	 * @param RequestStack $requestStack
	 */
	public function __construct(RequestStack $requestStack)
	{
		$this->index = 0;
		if($requestStack->getMasterRequest() !== null) {
			$this->setSession($requestStack->getMasterRequest()->getSession());
		}
	}

	/**
	 * Set session.
	 *
	 * @param SessionInterface $session
	 */
	public function setSession(SessionInterface $session)
	{
		$this->session = $session;
		$this->session->start();

		if(null === $this->session) {
			$this->messages = array();
			return;
		}

		$this->messages = $this->session->getFlashBag()->get('console_messages');
		if (null === $this->messages) {
			$this->messages = array();
		}
		$this->saveMessages();
	}

	/**
	 * Add a message to the console
	 *
	 * @param string $message Content of the message
	 * @param string $type Type of the message (SUCCESS|INFO|WARNING|DANGER)
	 * @param string $icon Icon of the message (fa awesome)
	 */
	public function add($message, $type = Message::TYPE_INFO, $icon = null)
	{
		if (!$icon) {
			$icon = self::$messageIcons[$type];
		}

		$this->messages[] = new Message($message, $type, $icon);
		$this->saveMessages();
	}

	/**
	 * Get message.
	 *
	 * @return Message[]
	 * @throws \Exception
	 */
	public function getMessages()
	{
		$messages = $this->messages;
		$this->messages = array();
		$this->saveMessages();

		return $messages;
	}

	/**
	 * Save Messages to be retrieved through session
	 */
	protected function saveMessages()
	{
		if(null !== $this->session) {
			$this->session->getFlashBag()->set('console_messages', $this->messages);
		}
	}
}
