<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Domain\Repository;

use Maispace\MaiGallery\Domain\Model\Gallery;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class GalleryRepository extends Repository
{
    protected $defaultOrderings = [
        'year' => QueryInterface::ORDER_DESCENDING,
        'crdate' => QueryInterface::ORDER_DESCENDING,
    ];

    public function findByYear(int $year): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('year', $year),
        );
        return $query->execute();
    }

    public function findByCategoryUid(int $categoryUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('categories', $categoryUid),
        );
        return $query->execute();
    }

    public function findFilteredAndSorted(int $categoryUid = 0, int $year = 0): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];

        if ($categoryUid > 0) {
            $constraints[] = $query->contains('categories', $categoryUid);
        }

        if ($year > 0) {
            $constraints[] = $query->equals('year', $year);
        }

        if ($constraints !== []) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        return $query->execute();
    }

    public function findDistinctYears(): array
    {
        $query = $this->createQuery();
        $query->setOrderings(['year' => QueryInterface::ORDER_DESCENDING]);
        $results = $query->execute(true);

        $years = [];
        foreach ($results as $row) {
            if (isset($row['year']) && $row['year'] > 0) {
                $years[$row['year']] = $row['year'];
            }
        }
        return array_values($years);
    }

    public function createQueryBuilderForPagination(int $categoryUid = 0, int $year = 0): QueryBuilder
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_maigallery_gallery');

        $queryBuilder
            ->select('*')
            ->from('tx_maigallery_gallery')
            ->orderBy('year', 'DESC')
            ->addOrderBy('crdate', 'DESC');

        if ($year > 0) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->eq('year', $queryBuilder->createNamedParameter($year, \PDO::PARAM_INT))
                );
        }

        if ($categoryUid > 0) {
            $queryBuilder
                ->leftJoin(
                    'tx_maigallery_gallery',
                    'sys_category_record_mm',
                    'mm',
                    $queryBuilder->expr()->eq('mm.uid_foreign', $queryBuilder->quoteIdentifier('tx_maigallery_gallery.uid'))
                )
                ->andWhere(
                    $queryBuilder->expr()->eq('mm.uid_local', $queryBuilder->createNamedParameter($categoryUid, \PDO::PARAM_INT))
                )
                ->andWhere(
                    $queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->createNamedParameter('tx_maigallery_gallery'))
                )
                ->andWhere(
                    $queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->createNamedParameter('categories'))
                );
        }

        return $queryBuilder;
    }
}
