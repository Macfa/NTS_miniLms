<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CourseCard extends Component
{
  protected string $title;
  protected string $image;
  protected string $description;
  protected int $id;
    /**
     * Create a new component instance.
     */
    public function __construct($id = 0, $title = 'test', $image = '', $description = 'test description')
    {
      $this->id = $id;
      $this->title = $title;
      $this->image = $image;
      $this->description = $description;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.course-card', [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'description' => $this->description,
        ]);
    }
}
