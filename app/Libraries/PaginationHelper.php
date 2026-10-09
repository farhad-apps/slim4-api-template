<?php

namespace App\Libraries;

class PaginationHelper
{
    private int $page = 1;
    private int $perPage = 15;
    private int $total = 0;

    public function __construct(int $page = 1, int $perPage = 15)
    {
        $this->page = max(1, $page);
        $this->perPage = max(1, $perPage);
    }

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }

    public function setTotal(int $total): self
    {
        $this->total = max(0, $total);
        return $this;
    }

    public function getLastPage(): int
    {
        if ($this->total === 0) {
            return 1;
        }

        return (int) ceil($this->total / $this->perPage);
    }

    public function getMetadata(): array
    {
        return [
            'current_page' => $this->page,
            'per_page' => $this->perPage,
            'total' => $this->total,
            'last_page' => $this->getLastPage(),
            'from' => $this->getOffset() + 1,
            'to' => min($this->getOffset() + $this->perPage, $this->total),
        ];
    }
}
