<?php

/*
 * This file is part of Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\CommentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Security\Authentication\UserInterface;

/**
 * Minimum implementation for threads.
 */
class Thread implements ThreadInterface, AuditableInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $entityId;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $commentCount = 0;

    /**
     * @var Collection
     */
    protected $comments;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var \DateTime
     */
    protected $changed;

    /**
     * @var UserInterface
     */
    protected $changer;

    /**
     * @var UserInterface
     */
    protected $creator;

    /**
     * @param string $type
     * @param string $entityId
     * @param Collection $comments
     * @param int $commentCount
     */
    public function __construct($type, $entityId, Collection $comments = null, $commentCount = 0)
    {
        $this->type = $type;
        $this->entityId = $entityId;
        $this->comments = $comments ?: new ArrayCollection();
        $this->commentCount = $commentCount;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }

    /**
     * {@inheritdoc}
     */
    public function increaseCommentCount()
    {
        ++$this->commentCount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function decreaseCommentCount()
    {
        --$this->commentCount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = $commentCount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * {@inheritdoc}
     */
    public function addComment(CommentInterface $comment)
    {
        $this->comments->add($comment);
        $comment->setThread($this);

        if ($comment->isPublished()) {
            $this->increaseCommentCount();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeComment(CommentInterface $comment)
    {
        $this->comments->removeElement($comment);

        if ($comment->isPublished()) {
            $this->decreaseCommentCount();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * {@inheritdoc}
     */
    public function getChanged()
    {
        return $this->changed;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * {@inheritdoc}
     */
    public function getChanger()
    {
        return $this->changer;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatorFullName()
    {
        if (!$this->getCreator()) {
            return '';
        }

        return $this->getCreator()->getFullName();
    }

    /**
     * {@inheritdoc}
     */
    public function getChangerFullName()
    {
        if (!$this->getChanger()) {
            return '';
        }

        return $this->getChanger()->getFullName();
    }
}
