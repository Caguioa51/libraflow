<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;

class RealBooksSeeder extends Seeder
{
	public function run(): void
	{
		$books = [
			// Filipino Literature & Classics
			['title' => 'Noli Me Tangere', 'author' => 'José Rizal', 'genre' => 'Filipino Literature', 'quantity' => 5],
			['title' => 'El Filibusterismo', 'author' => 'José Rizal', 'genre' => 'Filipino Literature', 'quantity' => 5],
			['title' => 'Florante at Laura', 'author' => 'Francisco Balagtas', 'genre' => 'Filipino Literature', 'quantity' => 3],
			['title' => 'Ibong Adarna', 'author' => 'Anonymous', 'genre' => 'Filipino Literature', 'quantity' => 3],

			// English Literature Classics (School Curriculum)
			['title' => 'Romeo and Juliet', 'author' => 'William Shakespeare', 'genre' => 'Classic Literature', 'quantity' => 4],
			['title' => 'Macbeth', 'author' => 'William Shakespeare', 'genre' => 'Classic Literature', 'quantity' => 4],
			['title' => 'The Merchant of Venice', 'author' => 'William Shakespeare', 'genre' => 'Classic Literature', 'quantity' => 3],
			['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'genre' => 'Classic Literature', 'quantity' => 3],
			['title' => 'Great Expectations', 'author' => 'Charles Dickens', 'genre' => 'Classic Literature', 'quantity' => 3],
			['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'genre' => 'Classic Literature', 'quantity' => 4],
			['title' => 'The Catcher in the Rye', 'author' => 'J.D. Salinger', 'genre' => 'Classic Literature', 'quantity' => 3],
			['title' => 'Lord of the Flies', 'author' => 'William Golding', 'genre' => 'Classic Literature', 'quantity' => 3],
			['title' => 'Animal Farm', 'author' => 'George Orwell', 'genre' => 'Classic Literature', 'quantity' => 4],
			['title' => '1984', 'author' => 'George Orwell', 'genre' => 'Classic Literature', 'quantity' => 3],

			// Young Adult Fiction
			['title' => 'The Fault in Our Stars', 'author' => 'John Green', 'genre' => 'Young Adult', 'quantity' => 4],
			['title' => 'The Hunger Games', 'author' => 'Suzanne Collins', 'genre' => 'Young Adult', 'quantity' => 5],
			['title' => 'Catching Fire', 'author' => 'Suzanne Collins', 'genre' => 'Young Adult', 'quantity' => 4],
			['title' => 'Mockingjay', 'author' => 'Suzanne Collins', 'genre' => 'Young Adult', 'quantity' => 4],
			['title' => 'Divergent', 'author' => 'Veronica Roth', 'genre' => 'Young Adult', 'quantity' => 4],
			['title' => 'Insurgent', 'author' => 'Veronica Roth', 'genre' => 'Young Adult', 'quantity' => 3],
			['title' => 'Allegiant', 'author' => 'Veronica Roth', 'genre' => 'Young Adult', 'quantity' => 3],
			['title' => 'The Maze Runner', 'author' => 'James Dashner', 'genre' => 'Young Adult', 'quantity' => 4],
			['title' => 'Wonder', 'author' => 'R.J. Palacio', 'genre' => 'Young Adult', 'quantity' => 4],
			['title' => 'The Giver', 'author' => 'Lois Lowry', 'genre' => 'Young Adult', 'quantity' => 4],

			// Educational & Reference
			['title' => 'Filipino-English Dictionary', 'author' => 'Various', 'genre' => 'Reference', 'quantity' => 3],
			['title' => 'World History', 'author' => 'Various', 'genre' => 'Educational', 'quantity' => 5],
			['title' => 'Basic Mathematics', 'author' => 'Various', 'genre' => 'Educational', 'quantity' => 6],
			['title' => 'General Science', 'author' => 'Various', 'genre' => 'Educational', 'quantity' => 5],
			['title' => 'Filipino Grammar and Composition', 'author' => 'Various', 'genre' => 'Educational', 'quantity' => 4],

			// Children's Literature
			['title' => 'Charlotte\'s Web', 'author' => 'E.B. White', 'genre' => 'Children\'s Literature', 'quantity' => 4],
			['title' => 'The Little Prince', 'author' => 'Antoine de Saint-Exupéry', 'genre' => 'Children\'s Literature', 'quantity' => 3],
			['title' => 'Matilda', 'author' => 'Roald Dahl', 'genre' => 'Children\'s Literature', 'quantity' => 4],
			['title' => 'Charlie and the Chocolate Factory', 'author' => 'Roald Dahl', 'genre' => 'Children\'s Literature', 'quantity' => 4],
			['title' => 'Where the Wild Things Are', 'author' => 'Maurice Sendak', 'genre' => 'Children\'s Literature', 'quantity' => 3],
			['title' => 'The Very Hungry Caterpillar', 'author' => 'Eric Carle', 'genre' => 'Children\'s Literature', 'quantity' => 5],

			// Philippine History & Culture
			['title' => 'Philippine History for Students', 'author' => 'Various', 'genre' => 'Philippine History', 'quantity' => 5],
			['title' => 'The Philippine Revolution', 'author' => 'Various', 'genre' => 'Philippine History', 'quantity' => 3],
			['title' => 'Filipino Heroes Biography', 'author' => 'Various', 'genre' => 'Biography', 'quantity' => 4],
			['title' => 'Traditional Filipino Tales', 'author' => 'Various', 'genre' => 'Filipino Folklore', 'quantity' => 3],

			// Science & Technology
			['title' => 'Introduction to Computer Science', 'author' => 'Various', 'genre' => 'Technology', 'quantity' => 4],
			['title' => 'Basic Physics', 'author' => 'Various', 'genre' => 'Science', 'quantity' => 5],
			['title' => 'Chemistry for Beginners', 'author' => 'Various', 'genre' => 'Science', 'quantity' => 4],
			['title' => 'Biology Fundamentals', 'author' => 'Various', 'genre' => 'Science', 'quantity' => 5],

			// Poetry & Literature
			['title' => 'Filipino Poetry Collection', 'author' => 'Various', 'genre' => 'Poetry', 'quantity' => 3],
			['title' => 'World Poetry Anthology', 'author' => 'Various', 'genre' => 'Poetry', 'quantity' => 3],
			['title' => 'Short Stories Collection', 'author' => 'Various', 'genre' => 'Short Stories', 'quantity' => 4],
		];

		foreach ($books as $b) {
			$category = Category::firstOrCreate(['name' => $b['genre']]);
			$author = Author::firstOrCreate(['name' => $b['author']]);

			$quantity = $b['quantity'] ?? 2; // Default to 2 copies if not specified

			Book::firstOrCreate(
				['title' => $b['title'], 'author_id' => $author->id],
				[
					'category_id' => $category->id,
					'genre' => $b['genre'],
					'status' => 'available',
					'quantity' => $quantity,
					'available_quantity' => $quantity,
					'description' => null,
				]
			);
		}
	}
}
