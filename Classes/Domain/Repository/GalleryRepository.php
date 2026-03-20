<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Domain\Repository;

use Maispace\MaiGallery\Domain\Model\Gallery;
use Maispace\MaiGallery\Domain\Model\GalleryCategory;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<Gallery>
 */
class GalleryRepository extends Repository
{
    protected $defaultOrderings = [
        'date' => QueryInterface::ORDER_DESCENDING,
        'sorting' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Find all galleries filtered by year and/or category.
     *
     * @return QueryResultInterface<Gallery>
     */
    public function findByYearAndCategory(?int $year = null, ?GalleryCategory $category = null): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];

        if ($year !== null) {
            $startDate = new \DateTime($year . '-01-01 00:00:00');
            $endDate = new \DateTime($year . '-12-31 23:59:59');
            $constraints[] = $query->greaterThanOrEqual('date', $startDate);
            $constraints[] = $query->lessThanOrEqual('date', $endDate);
        }

        if ($category !== null) {
            $constraints[] = $query->equals('category', $category);
        }

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        return $query->execute();
    }

    /**
     * Find all galleries for a specific year.
     *
     * @return QueryResultInterface<Gallery>
     */
    public function findByYear(int $year): QueryResultInterface
    {
        return $this->findByYearAndCategory($year);
    }

    /**
     * Find all galleries for a specific category.
     *
     * @return QueryResultInterface<Gallery>
     */
    public function findByCategory(GalleryCategory $category): QueryResultInterface
    {
        return $this->findByYearAndCategory(null, $category);
    }

    /**
     * Return a list of distinct years that have galleries.
     *
     * @return list<int>
     */
    public function findDistinctYears(): array
    {
        $query = $this->createQuery();
        $query->setOrderings(['date' => QueryInterface::ORDER_DESCENDING]);

        $result = $query->execute(true);
        $years = [];

        foreach ($result as $row) {
            if (isset($row['date'])) {
                $dateValue = $row['date'];
                if (is_int($dateValue)) {
                    $year = (int)(new \DateTime('@' . $dateValue))->format('Y');
                } elseif (is_string($dateValue) && $dateValue !== '') {
                    $year = (int)(new \DateTime($dateValue))->format('Y');
                } else {
                    continue;
                }
                if (!in_array($year, $years, true)) {
                    $years[] = $year;
                }
            }
        }

        rsort($years);

        return $years;
    }
}
