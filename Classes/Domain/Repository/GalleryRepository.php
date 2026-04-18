<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Domain\Repository;

use Maispace\MaiGallery\Domain\Model\Gallery;
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
            $query->equals('year', $year)
        );
        return $query->execute();
    }

    public function findByCategoryUid(int $categoryUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('categories', $categoryUid)
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
}
