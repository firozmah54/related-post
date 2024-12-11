<?php 

class Wedevs_Related_Posts_plugin{

    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_taxonomies']);

        add_filter('the_content', [$this, 'insert_post_data'], );
        add_filter('the_title',[$this, 'add_movie_year'], 10, 2);

        add_filter('the_content',[$this, 'add_movies_related_posts']);

        
    }

    public function register_post_type(){
        register_post_type('movie', [
            'label' => 'movie',
            'labels' => [
                'name'=>'movies',
                'singular_name'=>'movie',
                'add_new_item'=>'add new movie',
            ],

            'public'=>true,
            'has_archive'=>true,
            'texonomy' => [ 'genre', 'actor', 'director', 'years' ],
            'supports'=>['title','editor','thumbnail']
        ]);
    }

    public function register_taxonomies(){
        register_taxonomy( 'genre', 'movie', [
            'label' => 'genre',
            'labels' => [
                'name'=>'genres',
                'singular_name'=>'genre',
                'add_new_item'=>'add new genre',
            ],
            'hierarchical'=>true,
            'show_admin_column'=>true
        ]);
        register_taxonomy( 'actor', 'movie', [
            'label' => 'actor',
            'labels' => [
                'name'=>'actors',
                'singular_name'=>'actor',
                'add_new_item'=>'add new actor',
            ],
            'hierarchical'=>true,
            'show_admin_column'=>true
        ]);
        register_taxonomy( 'director', 'movie', [
            'label' => 'director',
            'labels' => [
                'name'=>'directors',
                'singular_name'=>'director',
                'add_new_item'=>'add new director',
            ],
            'hierarchical'=>true
        ]);
        register_taxonomy( 'years', 'movie', [
            'label' => 'Year',
            'labels' => [
                'name'=>'years',
                'singular_name'=>'year',
                'add_new_item'=>'add new year',
            ],
            'rewrite'=>[
                'slug'=>'year-movie',
            ],
            'hierarchical'=>true
        ]);
    }

    public function insert_post_data($content){
    // without link texonomy to the get_the_terms function
    //  dump(get_the_terms(get_the_ID(), 'genre'));

    //   if(!is_singular('movie')){
    //   //if it is not  type of movie then return the content 
    //     return $content;
    //   }

    $post= get_post(get_the_ID());
    if($post->post_type !=='movie'){
        return $content;
    }
        // with link texonomy to the get_the_terms function
     $genre= get_the_term_list(get_the_ID(), 'genre','', ', ');
     $actor= get_the_term_list(get_the_ID(), 'actor','', ', ');
     $director= get_the_term_list(get_the_ID(), 'director','', ', ');
     $year= get_the_term_list(get_the_ID(), 'years','', ', ');

     $info='<ul>';

     if($genre){
        $info.='<li>';
        $info.='<strong>Genre:</strong>';
        $info.=$genre;
        $info.='</li>';
     }
     if($actor){
        $info.='<li>';
        $info.='<strong>Actor:</strong>';
        $info.=$actor;
        $info.='</li>';
     }
     if($director){
        $info.='<li>';
        $info.='<strong>Director:</strong>';
        $info.=$director;
        $info.='</li>';
     }
     if(!is_wp_error($year) && $year){
        $info.='<li>';
        $info.='<strong>Year:</strong>';
        $info.=$year;
        $info.='</li>';
     }
        $info.='</ul>';
        return $content . $info;
    }

    public function add_movie_year($title, $id){

        $post= get_post(get_the_ID());

        if($post->post_type !=='movie'){
            return $title;
        }

        $years= get_the_terms(get_the_ID(), 'years', '', ', ');

        if($years){
            return $title . ' ( '. $years[0]->name . ' ) ';
        }
        return $title;
        
    }

    public function add_movies_related_posts($content){
        $genre= get_the_terms(get_the_ID(), 'genre');
           

            if(!$genre){
                return $content;
            }
            //have to wp_query show the related posts 
           

        $query= new WP_Query([
                'post_type'=>'movie',
                'post__not_in'=>[get_the_ID()],
                'tax_query'=>[
                    'relation'=>'or',
                    [
                        'taxonomy'=>'genre',
                        'terms'=>wp_list_pluck($genre,'term_id' ),
                    ]
                ]
            ]);

            if(!$query->have_posts()){
                return $content;
            }

        $related='<h2>Related Movies (Related posts by genre)</h2>';
        foreach($query->get_posts() as $movie){

            $related.='<li>';
            $related.='<a href="'.get_the_permalink($movie->ID).'">';
            $related.=$movie->post_title;
            $related.='</a>';
            $related.='</li>';
            
        }
       
        return $content .$related; 

    }
}


//helper function 

function dump($var){
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}