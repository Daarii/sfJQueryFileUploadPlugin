## How to setup

In ``config/ProjectConfiguration.class.php``

```php
class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfJQueryFileUploadPlugin');
    ...
```

In ``apps/backend/config/settings.yml``

```yaml
all:
  .settings:
    enabled_modules: [default, jQueryFileUpload, ...]
```

In ``apps/backend/config/routing.yml``

```yaml
jquery_file_upload_ajax:
  url:   /jquery-file-upload
  param: { module: jQueryFileUpload, action: uploadAjax }
```

## How to use (demo)
In ``schema.yml``

```yaml
Demo:
  columns:
    pictures:
      type:     clob
    #other fields ...
```

In your form class

```phpclass DemoForm extends BaseZarForm
{
    public function configure()
    {
        $this->setWidget('pictures', new sfWidgetFormJQueryFileUpload());
    }

    public function updateDefaultsFromObject()
    {
        parent::updateDefaultsFromObject();
        if(!$this->isNew()){
            $this->setDefault('pictures', implode(" ",$this->getObject()->getPictures()));
        }
    }
    ...
```

If you use Admin Generator with sfTwitterBootstrapPlugin then it looks like:

Preview:

![Preview](https://github.com/enkuso/sfJQueryFileUploadPlugin/raw/master/doc/preview.png)