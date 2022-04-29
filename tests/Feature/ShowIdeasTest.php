<?php

namespace Tests\Feature;

use App\Models\Idea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowIdeasTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */

    public function list_of_ideas_shows_on_main_page(): void
    {

        $ideaOne = Idea::factory()->create([
            'title' => 'Mt First idea',
            'description' => "Description One"
        ]);
        $ideaTwo = Idea::factory()->create([
            'title' => 'Mt Second idea',
            'description' => "Description Second"
        ]);


        $response = $this->get(route('index'));

        $response->assertSuccessful();

        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->description);
        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->description);

    }

    /**
     *
     * @test
     */
    public function Single_idea_show_on_show_page(): void
    {

        $idea = Idea::factory()->create([
            'title' => 'Mt First idea',
            'description' => "Description One"
        ]);


        $response = $this->get(route('idea', $idea));

        $response->assertSuccessful();

        $response->assertSee($idea->title);
        $response->assertSee($idea->description);

    }

    /**
     * @test
     */
    public function ideas_pagination_work(): void
    {

        Idea::factory('6')->create();

        $ideaOne = Idea::find(1);
        $ideaOne->title = "My First Idea";
        $ideaOne->save();

        $ideaSix = Idea::find(6);
        $ideaSix->title = "My Six Idea";
        $ideaSix->save();

        $response = $this->get('/');
        $response->assertSee($ideaOne->title);
        $response->assertDontSee($ideaSix->title);

        $response = $this->get('/?page=2');
        $response->assertSee($ideaSix->title);
        $response->assertDontSee($ideaOne->title);


    }

    /**
     * @test
     */
    public function same_name_different_slug(): void
    {

        $ideaOne = Idea::factory()->create([
            'title' => 'My First Idea',
            'description' => "Description One"
        ]);
        $ideaTwo = Idea::factory()->create([
            'title' => 'My First Idea',
            'description' => "Description One"
        ]);

        $response = $this->get(route('idea', $ideaOne));

        $response->assertSuccessful();

        $this->assertSame('ideas/my-first-idea', request()->path());



        $response = $this->get(route('idea', $ideaTwo));

        $response->assertSuccessful();

        $this->assertSame('ideas/my-first-idea-2', request()->path() );

    }
}