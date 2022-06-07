<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="notes")
 */
class Note {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
	public $id;

	/**
     * @ORM\Column(type="string")
     */
	public $title;

	/**
     * @ORM\Column(type="string")
     */
	public $text;
	
	/**
     * @ORM\Column(type="string")
     */
	public $created_at;
	
	/**
     * @ORM\Column(type="string")
     */
	public $updated_at;


}