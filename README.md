restart pg
invoke-rc.d postgresql restart

cd /etc/nginx/sites-available
sudo nano /etc/nginx/sites-available/default
service nginx restart

cd /var/www/html/site/public

cd /etc/php/7.2/cli

api result always 1 structure
error: some text
success: some text
result: some data

when project done add auth:api middleware to routes

/*  $category->getCollection()->transform(function ($category) {
        $category->image = isset($category->image) ? asset('uploads/categories/' . $category->image) : null;
        return $category;
    }); 

     $allCategory = $category->map(function ($category) {
        return [
            "id" => $category->id,
            "title" => $category->title,
            "image" => isset($category->image) ? asset('uploads/categories/' . $category->image) : null,
            "parent_id" => $category->parent_id,
            "created_at" => $category->created_at,
        ];
    }); */