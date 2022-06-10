# The MVC Workflow
In the last section we saw how to build our `Model` abstract class, whose `getDB()` method opened a gate to the database. In the `User` model we have a method to test things out:
```php
public function getUsers()
{
    $db = static::getDB();
    $stmt = $db->query('SELECT * FROM users');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

## The Controller Abstract class
Once a request triggers an action in a **controller** (thanks to our `Router` instance), this one may retrieve some data (using an instance of a **model**), loads whatever **view** file is needed, and injects into it the retrieved data. This class is gonna need just two methods:

* `load(string $model)`: which simply loads the file where we have defined whatever model class is needed.
* `render(string $view, array $data)`: which simply loads the **view file**, passing down whatever is in the `data` array.

> For rendering the **views** we could have created a `View` class with a `render` method, and invoke it in the controller as `View::render($index, $data);`. But hey, next time ;-)

## The Views
In an MVC framework, the views are what we use to present the information to the user. They are usually made using some [template markup](https://en.wikipedia.org/wiki/Web_template_system), but since external libraries are forbidden for this project (and I'm not gonna reinvent the wheel writing a building engine), they will contain mostly HTML and just enough PHP to display the data.

<p align="center"><img src="./images/controller_view.jpg" height="250" /></p>

Views are **stupid**, they don't contain any logic to deal with the database, the session or *anyting* like that. They should only contain a minimum amount of PHP, mostly `echo` statements, and some loops to print stuff repeatedly. This makes easy for a designer to work on the view files without having to touch the code under the controllers and models (separation of concerns).

> Once a request triggers an action in a **controller**, this one may retrieve some data (using an instance of a **model**), loads whatever **view** file is needed, and injects into it the retrieved data.

---
[:arrow_backward:][back] ║ [:house:][home] ║ [:arrow_forward:][next]

<!-- navigation -->
[home]: ../README.md
[back]: ./model_class.md
[next]: #