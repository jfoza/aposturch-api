<?php

namespace App\Features\Base\Http\Pagination;

class PaginationOrder
{
    private ?string $columnName;
    private ?string $columnOrder;

    private ?int $page;
    private ?int $perPage;

    public function __construct() {
        $this->columnName = null;
        $this->columnOrder = null;
        $this->page = null;
        $this->perPage = null;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @param string|null $columnName
     */
    public function setColumnName(?string $columnName): void
    {
        if(is_null($columnName)) {
            $columnName = 'created_at';
        }

        $this->columnName = $columnName;
    }

    /**
     * @return string
     */
    public function getColumnOrder(): string
    {
        return $this->columnOrder;
    }

    /**
     * @param string|null $columnOrder
     */
    public function setColumnOrder(?string $columnOrder): void
    {
        if(is_null($columnOrder)) {
            $columnOrder = 'DESC';
        }

        $this->columnOrder = $columnOrder;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @param int|null $page
     */
    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int|null
     */
    public function getPerPage(): int|null
    {
        return $this->perPage;
    }

    /**
     * @param int|null $perPage
     */
    public function setPerPage(?int $perPage): void
    {
        if(is_null($perPage)) {
            $perPage = 15;
        }

        $this->perPage = $perPage;
    }
}
