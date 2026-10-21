<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]

class Shop extends Component
{
    use WithPagination;

    // Filter properties with URL query string binding
    #[Url(as: 'category')]
    public ?string $categorySlug = null;

    #[Url]
    public string $search = '';

    #[Url]
    public float $minPrice = 0;

    #[Url]
    public float $maxPrice = 10000;

    #[Url]
    public string $sortBy = 'latest';

    #[Url]
    public array $categories = [];

    #[Url]
    public array $ratings = [];

    // Available sort options
    public array $sortOptions = [
        'latest' => 'Latest',
        'price_asc' => 'Price: Low to High',
        'price_desc' => 'Price: High to Low',
        'name_asc' => 'Name: A to Z',
        'name_desc' => 'Name: Z to A',
    ];

    // Available rating filter options
    public array $ratingOptions = [
        '5' => '4.5 & up',
        '4' => '4.0 & up',
        '3' => '3.0 & up',
        '2' => '2.0 & up',
        '1' => '1.0 & up',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => 10000],
        'sortBy' => ['except' => 'latest'],
        'categories' => ['except' => []],
        'ratings' => ['except' => []],
    ];

    public function mount($categorySlug = null): void
    {
        $this->categorySlug = $categorySlug;

        // Initialize max price from products if not set
        if ($this->maxPrice === 10000) {
            $this->maxPrice = (float) Product::max('price') ?: 10000;
        }
    }

    /**
     * Reset pagination when any filter is updated
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingMinPrice(): void
    {
        $this->resetPage();
    }

    public function updatingMaxPrice(): void
    {
        $this->resetPage();
    }

    public function updatingCategories(): void
    {
        $this->resetPage();
    }

    public function updatingRatings(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    /**
     * Toggle category filter
     */
    public function toggleCategory($categoryId): void
    {
        $categoryId = (int) $categoryId;
        if (($key = array_search($categoryId, $this->categories, true)) !== false) {
            unset($this->categories[$key]);
        } else {
            $this->categories[] = $categoryId;
        }
        $this->categories = array_values(array_unique($this->categories));
    }

    /**
     * Toggle rating filter
     */
    public function toggleRating($rating): void
    {
        if (($key = array_search($rating, $this->ratings)) !== false) {
            unset($this->ratings[$key]);
        } else {
            $this->ratings[] = $rating;
        }
        $this->ratings = array_values(array_unique($this->ratings));
    }

    /**
     * Reset all filters to their default values
     */
    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'minPrice',
            'maxPrice',
            'categories',
            'ratings',
            'sortBy',
        ]);
        $this->maxPrice = (float) Product::max('price') ?: 10000;
    }

    /**
     * Get all available categories with product counts
     */
    #[Computed]
    public function allCategories()
    {
        return Category::query()
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get the filtered and sorted products
     */
    protected function getFilteredProducts()
    {
        $query = Product::query()
            ->with('category')
            ->where('is_active', true);

        // Apply category filter if category slug is provided
        if ($this->categorySlug) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->categorySlug);
            });
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        // Apply price range filter
        $query->whereBetween('price', [
            $this->minPrice,
            min($this->maxPrice, 1000000), // Prevent extremely large numbers
        ]);

        // Apply category filter
        if (! empty($this->categories)) {
            $query->whereIn('category_id', $this->categories);
        }

        // Apply rating filter
        if (! empty($this->ratings)) {
            $query->where(function ($q) {
                foreach ($this->ratings as $rating) {
                    $q->orWhere('rating', '>=', $rating);
                }
            });
        }

        // Apply sorting
        $this->applySorting($query);

        return $query->paginate(12);
    }

    /**
     * Apply sorting to the query
     */
    protected function applySorting(Builder $query): void
    {
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
    }

    /**
     * Get the current category if a category filter is applied
     */
    #[Computed]
    public function currentCategory()
    {
        if (! $this->categorySlug) {
            return null;
        }

        return Category::where('slug', $this->categorySlug)->first();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.shop', [
            'products' => $this->getFilteredProducts(),
            'allCategories' => $this->allCategories,
            'currentCategory' => $this->currentCategory,
        ]);
    }
}
