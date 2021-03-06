<?php
/**
 * News.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link https://ziku.la
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\NewsModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Zikula\CategoriesModule\Entity\AbstractCategoryAssignment;

/**
 * Entity extension domain class storing message categories.
 *
 * This is the base category class for message entities.
 */
abstract class AbstractMessageCategoryEntity extends AbstractCategoryAssignment
{
    /**
     * @ORM\ManyToOne(targetEntity="\MU\NewsModule\Entity\MessageEntity", inversedBy="categories")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id")
     * @var \MU\NewsModule\Entity\MessageEntity
     */
    protected $entity;
    
    /**
     * Get reference to owning entity.
     *
     * @return \MU\NewsModule\Entity\MessageEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }
    
    /**
     * Set reference to owning entity.
     *
     * @param \MU\NewsModule\Entity\MessageEntity $entity
     */
    public function setEntity(/*\MU\NewsModule\Entity\MessageEntity */$entity)
    {
        $this->entity = $entity;
    }
}
