# The Controller Abstract class
Once a URL request triggers an action in a **controller** (thanks to our `Router` instance), this one may retrieve some data (using an instance of a **model**), loads whatever **view** file is needed, and injects into it the retrieved data. This is gonna be common logic for **all controllers**, so it's a good idea to create an abstract class to define this behaviour. Such a class is gonna need just two methods:

* `load(string $model)`: which simply **loads the file** where we have defined whatever model class is needed, and returns an **instance** of it.
* `render(string $view, array $data)`: which simply loads the **view file**, passing down whatever is in the `data` array. By the way, just adding the **data** as a parameter in this method, is enough to make it available in the view (yeah, it surprised me too).

> For rendering the **views** we could have created a `View` class with a `render` method, and invoke it in the controller as `View::render($index, $data);`. But hey, next time ;-)

## A Controller example
Let's write a `Users` controller to test the abstract class mentioned above:
```php
class Users extends Controller
{
    public function index()
    {
        $userInstance = $this->load('User');
        $data = $userInstance->getUsers();
        $this->render('Users', $data);
    }
}
```

As you can see, the `index` method (known as action in MVC) is using the abstract methods to:

1. Load a model file, and instantiate the `User` model.
2. Load a view file, and pass down the data it got from the model.

**That** is known as the **MVC Workflow**!

> In MVC, by convention, controllers are named in **plural**, e.g. `Users`, `Posts`, etc. Whereas **models** are **singular**, e.g. `User`, `Post`, etc.

---
[:arrow_backward:][back] ║ [:house:][home] ║ [:arrow_forward:][next]

<!-- navigation -->
[home]: ../README.md
[back]: ./model_class.md
[next]: ./views.md