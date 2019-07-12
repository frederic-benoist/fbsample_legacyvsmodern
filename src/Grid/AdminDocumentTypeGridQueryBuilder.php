<?php
/**
 * 2013-2019 Frédéric BENOIST
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * Use, copy, modification or distribution of this source file without written
 * license agreement from Frédéric BENOIST is strictly forbidden.
 *
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence Academic Free License (AFL 3.0)
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de Frédéric BENOIST est
 * expressement interdite.
 *
 *  @author    Frédéric BENOIST
 *  @copyright 2013-2019 Frédéric BENOIST <https://www.fbenoist.com>
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace FBenoist\FbSampleLegacyVsModern\Grid;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

/**
 * Class AdminDocumentTypeGridQueryBuilder
 */
final class AdminDocumentTypeGridQueryBuilder extends AbstractDoctrineQueryBuilder
{

    /**
     * @var int
     */
    private $contextLangId;

    /**
     * @var int
     */
    private $contextShopId;

    /**
     * @param Connection $connection
     * @param string $dbPrefix
     * @param int $contextLangId
     * @param int $contextShopId
     */
    public function __construct(Connection $connection, $dbPrefix, $contextLangId, $contextShopId)
    {
        parent::__construct($connection, $dbPrefix);
        $this->contextLangId = $contextLangId;
        $this->contextShopId = $contextShopId;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getBaseQuery();
        $qb->orderBy(
            $searchCriteria->getOrderBy(),
            $searchCriteria->getOrderWay()
        )
        ->setFirstResult($searchCriteria->getOffset())
        ->setMaxResults($searchCriteria->getLimit());

        foreach ($searchCriteria->getFilters() as $filterName => $filterValue) {
            if ('active' === $filterName) {
                (bool) $filterValue ?
                    $qb->where('rec.active = 1') :
                    $qb->where('rec.active = 0');
                continue;
            }
            $qb->andWhere("$filterName LIKE :$filterName");
            $qb->setParameter($filterName, '%'.$filterValue.'%');
        }
        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getBaseQuery();
        $qb->select('COUNT(rec.id_documenttype)');

        return $qb;
    }

    /**
     * Base query is the same for both searching and counting
     *
     * @return QueryBuilder
     */
    private function getBaseQuery()
    {
        $selectArray = ['rec.id_documenttype'];
        $selectArray[] = 'rec.active';
        $selectArray[] = 'rec.maxsize';
        $selectArray[] = 'recl.name';

        return $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix.'documenttype', 'rec')
            ->select(implode(', ', $selectArray))
            ->leftJoin(
                'rec',
                $this->dbPrefix . 'documenttype_lang',
                'recl',
                'recl.id_documenttype = rec.id_documenttype AND recl.id_lang = :context_lang_id'
            )
            ->setParameter('context_lang_id', $this->contextLangId)
        ;
    }
}
