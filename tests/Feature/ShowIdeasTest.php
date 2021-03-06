<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
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

        $CategoryOne = Category::factory()->create(["name" => "Category 1"]);
        $CategoryTwo = Category::factory()->create(["name" => "Category 2"]);

        $user = User::factory()->create();
        $statusOne = Status::create([
            'name' => 'open',
            'color' => '#000'
        ]);
        $statusTwo = Status::create([
            'name' => 'close',
            'color' => '#ec454f'
        ]);


        $ideaOne = Idea::factory()->create([
            'title' => 'Mt First idea',
            'user_id'=>$user->id,
            'category_id' => $CategoryOne->id,
            'status_id'=>$statusOne->id,
            'description' => "Description One"
        ]);
        $ideaTwo = Idea::factory()->create([
            'title' => 'Mt Second idea',
            'user_id'=>$user->id,
            'category_id' => $CategoryTwo->id,
            'status_id' => $statusTwo->id,
            'description' => "Description Second"
        ]);


        $response = $this->get(route('index'));

        $response->assertSuccessful();

        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->description);
        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->description);
        $response->assertSee($CategoryOne->name);
        $response->assertSee($CategoryTwo->name);
        $response->assertSee($statusOne->name);
        $response->assertSee($statusTwo->name);

    }

    /**
     *
     * @test
     */
    public function Single_idea_show_on_show_page(): void
    {
        $Category = Category::factory()->create(["name" => "Category 1"]);
        $status = Status::create([
            'name' => 'open',
            'color' => '#000'
        ]);
        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            'title' => 'Mt First idea',
            'user_id'=>$user->id,
            'category_id' => $Category->id,
            'status_id' => $status->id,
            'description' => "Description One"
        ]);


        $response = $this->get(route('idea', $idea));

        $response->assertSuccessful();

        $response->assertSee($idea->title);
        $response->assertSee($idea->description);
        $response->assertSee($Category->name);
        $response->assertSee($status->name);

    }

    /**
     * @test
     */
    public function ideas_pagination_work(): void
    {

        $Category = Category::factory()->create(["name" => "Category"]);
       $status = Status::create([
            'name' => 'open',
            'color' => '#000'
        ]);
        $user = User::factory()->create();

        Idea::factory('6')->create([
            'category_id' => $Category->id,
            'status_id' => $status->id,
            'user_id'=>$user->id,

        ]);



        $ideaOne = Idea::find(1);
        $ideaOne->title = "My First Idea";
        $ideaOne->save();

        $ideaSix = Idea::find(6);
        $ideaSix->title = "My Six Idea";
        $ideaSix->save();

        $response = $this->get('/');
        $response->assertSee($ideaSix->title);
        $response->assertDontSee($ideaOne->title);

        $response = $this->get('/?page=2');
        $response->assertSee($ideaOne->title);
        $response->assertDontSee($ideaSix->title);


    }

    /**
     * @test
     */
    public function same_name_different_slug(): void
    {

        $CategoryOne = Category::factory()->create(["name" => "Category 1"]);
        $CategoryTwo = Category::factory()->create(["name" => "Category 2"]);
        $statusOne = Status::create([
            'name' => 'open',
            'color' => '#000'
        ]);
        $user = User::factory()->create();
        $statusTwo = Status::create([
            'name' => 'close',
            'color' => '#ec454f'
        ]);
        $ideaOne = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id'=>$user->id,
            'category_id' => $CategoryOne->id,
            'status_id'=>$statusOne->id,
            'description' => "Description One"
        ]);
        $ideaTwo = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id'=>$user->id,
            'category_id' => $CategoryTwo->id,
            'status_id'=>$statusTwo->id,
            'description' => "Description One"
        ]);

        $response = $this->get(route('idea', $ideaOne));

        $response->assertSuccessful();

        $this->assertSame('ideas/my-first-idea', request()->path());


        $response = $this->get(route('idea', $ideaTwo));

        $response->assertSuccessful();

        $this->assertSame('ideas/my-first-idea-2', request()->path());

    }


    /**
     * @test
     */
    public function in_app_back_button_works_when_index_page_visited(): void
    {

        $CategoryOne = Category::factory()->create(["name" => "Category 1"]);
        $CategoryTwo = Category::factory()->create(["name" => "Category 2"]);

        $user = User::factory()->create();
        $statusOne = Status::create([
            'name' => 'open',
            'color' => '#000'
        ]);
        $statusTwo = Status::create([
            'name' => 'close',
            'color' => '#ec454f'
        ]);


        $ideaOne = Idea::factory()->create([
            'title' => 'Mt First idea',
            'user_id'=>$user->id,
            'category_id' => $CategoryOne->id,
            'status_id'=>$statusOne->id,
            'description' => "Description One"
        ]);



        $response = $this->get("http://vote-app.test/?category=Category%201");
        $responseTwo = $this->get(route('idea', $ideaOne));


        $this->assertStringContainsString("http://vote-app.test/?category=Category%201",$responseTwo['backUrl']);

    }
    /**
     * @test
     */
    public function in_app_back_button_works_when_show_page_visited(): void
    {

        $CategoryOne = Category::factory()->create(["name" => "Category 1"]);
        $CategoryTwo = Category::factory()->create(["name" => "Category 2"]);

        $user = User::factory()->create();
        $statusOne = Status::create([
            'name' => 'open',
            'color' => '#000'
        ]);
        $statusTwo = Status::create([
            'name' => 'close',
            'color' => '#ec454f'
        ]);


        $ideaOne = Idea::factory()->create([
            'title' => 'Mt First idea',
            'user_id'=>$user->id,
            'category_id' => $CategoryOne->id,
            'status_id'=>$statusOne->id,
            'description' => "Description One"
        ]);


        $responseTwo = $this->get(route('idea', $ideaOne));


        $this->assertEquals(route('index'),$responseTwo['backUrl']);

    }

}
