# MultiDocConfig

_Class representing the MultiDoc Configuration_

## Summary

### Public methods

__construct()
getIncludeDirectories()
setIncludeDirectories()
addIncludeDirectory()
getExcludeDirectories()
setExcludeDirectories()
addExcludeDirectory()
getOutputTo()
setOutputTo()
getOutputFormat()
setOutputFormat()
getAssetDirectory()
setAssetDirectory()
getHtmlDirectory()
setHtmlDirectory()
getMarkdownDirectory()
setMarkdownDirectory()
getFontsDirectory()
setFontsDirectory()

## Properties

### includeDirectories (protected)

```php
includeDirectories: string[]
```

Directories to include

### excludeDirectories (protected)

```php
excludeDirectories: string[]
```

Directories to exclude

### outputTo (protected)

```php
outputTo: string
```

Directory to which the docs should be published

### outputFormat (protected)

```php
outputFormat: string
```

The output format

### assetDirectory (protected)

```php
assetDirectory: string
```

The directory where the assets are stored

### htmlDirectory (protected)

```php
htmlDirectory: string
```

The directory where the HTML markup files are stored

### markdownDirectory (protected)

```php
markdownDirectory: string
```

The directory where the markdown markup files are stored

### fontsDirectory (protected)

```php
fontsDirectory: string
```

The directory where the font files are stored

## Methods

### __construct (public)

```php
__construct()
```

Constructor

### getIncludeDirectories (public)

```php
getIncludeDirectories()
```

Get the directories to search in

### setIncludeDirectories (public)

```php
setIncludeDirectories(array $directories)
```

Set a bunch of directories to search in

### addIncludeDirectory (public)

```php
addIncludeDirectory(string $directory)
```

Add a directory to search in

### getExcludeDirectories (public)

```php
getExcludeDirectories()
```

Get the directories to exclude from search

### setExcludeDirectories (public)

```php
setExcludeDirectories(array $directories)
```

Set a bunch of directories to exclude from search

### addExcludeDirectory (public)

```php
addExcludeDirectory(string $directory)
```

Add a directory to exclude from search

### getOutputTo (public)

```php
getOutputTo()
```

Get the directory to which the documentation is saved

### setOutputTo (public)

```php
setOutputTo(string $outputTo)
```

Set the directory to which the documentation is saved

### getOutputFormat (public)

```php
getOutputFormat()
```

Get the format in which the documentation is rendered

### setOutputFormat (public)

```php
setOutputFormat(string $outputFormat)
```

Set the directory to which the documentation is saved

### getAssetDirectory (public)

```php
getAssetDirectory()
```

Get the directory where the assets are stored

### setAssetDirectory (public)

```php
setAssetDirectory(string $assetDirectory)
```

Set the directory where the assets are stored

### getHtmlDirectory (public)

```php
getHtmlDirectory()
```

Get the directory where the html markup files are stored

### setHtmlDirectory (public)

```php
setHtmlDirectory(string $htmlDirectory)
```

Set the directory where the html markup files are stored

### getMarkdownDirectory (public)

```php
getMarkdownDirectory()
```

Get the directory where the markdown markup files are stored

### setMarkdownDirectory (public)

```php
setMarkdownDirectory(string $markdownDirectory)
```

Set the directory where the markdown markup files are stored

### getFontsDirectory (public)

```php
getFontsDirectory()
```

Get the directory where the font files are stored

### setFontsDirectory (public)

```php
setFontsDirectory(string $fontsDirectory)
```

Set the directory where the font files are stored

# MultiDocApplication

_Class representing the MultiDoc Console Application_

## Summary

### Public methods

__construct()

## Methods

### __construct (public)

```php
__construct(string $name = &#039;UNKNOWN&#039;, string $version = &#039;UNKNOWN&#039;)
```

Constructor

# MultiDocApplicationCreateCommand

_Class representing the MultiDoc Console Application &quot;Create&quot;-Commands_

## Summary

### Protected methods

configure()
execute()

## Methods

### configure (protected)

```php
configure()
```



### execute (protected)

```php
execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
```



# MultiDocApplicationListRenderers

_Class representing the MultiDoc Console Application &quot;Create&quot;-Commands_

## Summary

### Protected methods

configure()
execute()

## Methods

### configure (protected)

```php
configure()
```



### execute (protected)

```php
execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
```



# MultiDocCreatorServiceInterface

_Interface for a service class which will create the documentation_

## Summary

### Public methods

render()

## Methods

### render (public)

```php
render()
```

Starts the creation of the documentation

# MultiDocFinderServiceInterface

_Interface for a service class which will give us all files to handle_

## Summary

### Public methods

getAllFiles()
getAllFilesAsPhpDocLocalFiles()

## Methods

### getAllFiles (public)

```php
getAllFiles()
```

Get all found files

### getAllFilesAsPhpDocLocalFiles (public)

```php
getAllFilesAsPhpDocLocalFiles()
```

Get all files as a PHPdoc LocalFile

# MultiDocMarkupServiceInterface

_Interface for a service class which renders the markup_

## Summary

### Public methods

initializeService()
getMarkupTemplateDirectory()
getMarkupOutput()
addToMarkupOutput()
render()
renderAndAddToOutput()
writeHeader()
writeSummary()
writeConstants()
writeProperties()
writeMethods()
createFromClass()
createFromInterface()
createFromTrait()

## Methods

### initializeService (public)

```php
initializeService()
```

Initialize (e.g. the internal markup Content Container)

### getMarkupTemplateDirectory (public)

```php
getMarkupTemplateDirectory()
```

Get the directory where all the markup template files are located

### getMarkupOutput (public)

```php
getMarkupOutput()
```

Return the created markup

### addToMarkupOutput (public)

```php
addToMarkupOutput(string $add)
```

Add data to markup output

### render (public)

```php
render(string $name, array $data = array())
```

Render a markup

### renderAndAddToOutput (public)

```php
renderAndAddToOutput(string $name, array $data = array())
```

Render a markup and add the rendered output to internal markup storage

### writeHeader (public)

```php
writeHeader(string $name, string $summary, string $description)
```

Write Header

### writeSummary (public)

```php
writeSummary(array $constants, array $properties, array $methods)
```

Write a summary

### writeConstants (public)

```php
writeConstants(array $constants)
```

Write constants

### writeProperties (public)

```php
writeProperties(array $properties)
```

Write properties

### writeMethods (public)

```php
writeMethods(array $methods)
```

Write methods

### createFromClass (public)

```php
createFromClass(\phpDocumentor\Reflection\Php\Class_ $class)
```

Generate class description

### createFromInterface (public)

```php
createFromInterface(\phpDocumentor\Reflection\Php\Interface_ $interface)
```

Generate Interface description

### createFromTrait (public)

```php
createFromTrait(\phpDocumentor\Reflection\Php\Trait_ $trait)
```

Generate Trait description

# MultiDocRenderServiceInterface

_Interface for a service class which will render the documentation_

## Summary

### Public methods

setLocalFiles()
render()

## Methods

### setLocalFiles (public)

```php
setLocalFiles(array $files)
```

Set the files which are to handle

### render (public)

```php
render()
```

Render the documentation from files

# MultiDocRendererInterface

_Interface for a service class which renders the output documents_

## Summary

### Public methods

setReflectedFiles()
render()

## Methods

### setReflectedFiles (public)

```php
setReflectedFiles(array $files)
```

Set the file to render

### render (public)

```php
render()
```

Render the file

# MultiDocTwigServiceInterface

_Interface for service class which will render the twig templates_

## Summary

### Public methods

addTemplateDirectory()
render()

## Methods

### addTemplateDirectory (public)

```php
addTemplateDirectory(string $directory)
```

Add a directory where to find the needed templates

### render (public)

```php
render(string $name, array $data)
```

Render a twig remplate

# MultiDocRendererMultipleMarkDown

_service class which renders the output documents as an single markdown document_

## Summary

### Public methods

__construct()
setReflectedFiles()
render()

### Private methods

renderSingleMarkDown()
renderClass()
renderInterface()
renderTrait()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### markupService (protected)

```php
markupService: \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
```

The internal markup service

### reflectedFiles (protected)

```php
reflectedFiles
```

Files to handle

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### setReflectedFiles (public)

```php
setReflectedFiles(array $files)
```



### render (public)

```php
render()
```



### renderSingleMarkDown (private)

```php
renderSingleMarkDown(string $destinationFilename)
```

Render a single markdown file

### renderClass (private)

```php
renderClass(mixed $class)
```

Render a class markdown file

### renderInterface (private)

```php
renderInterface(mixed $interface)
```

Render a interface markdown file

### renderTrait (private)

```php
renderTrait(mixed $interface)
```

Render a interface markdown file

# MultiDocRendererSingleMarkDown

_service class which renders the output documents as an single markdown document_

## Summary

### Public methods

__construct()
setReflectedFiles()
render()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### markupService (protected)

```php
markupService: \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
```

The internal markup service

### reflectedFiles (protected)

```php
reflectedFiles
```

Files to handle

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### setReflectedFiles (public)

```php
setReflectedFiles(array $files)
```



### render (public)

```php
render()
```



# MultiDocRendererMultipleMarkDown

_service class which renders the output documents as an single markdown document_

## Summary

### Public methods

__construct()
setReflectedFiles()
render()

### Private methods

renderSingleMarkDown()
renderClass()
renderInterface()
renderTrait()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### markupService (protected)

```php
markupService: \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
```



### htmlConverter (protected)

```php
htmlConverter: \League\HTMLToMarkdown\HtmlConverter
```



### reflectedFiles (protected)

```php
reflectedFiles
```

Files to handle

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### setReflectedFiles (public)

```php
setReflectedFiles(array $files)
```



### render (public)

```php
render()
```



### renderSingleMarkDown (private)

```php
renderSingleMarkDown(string $destinationFilename)
```

Render a single markdown file

### renderClass (private)

```php
renderClass(mixed $class)
```

Render a class markdown file

### renderInterface (private)

```php
renderInterface(mixed $interface)
```

Render a interface markdown file

### renderTrait (private)

```php
renderTrait(mixed $interface)
```

Render a interface markdown file

# MultiDocRendererSingleMarkDown

_service class which renders the output documents as an single markdown document_

## Summary

### Public methods

__construct()
setReflectedFiles()
render()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### markupService (protected)

```php
markupService: \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
```



### htmlConverter (protected)

```php
htmlConverter: \League\HTMLToMarkdown\HtmlConverter
```



### reflectedFiles (protected)

```php
reflectedFiles
```

Files to handle

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### setReflectedFiles (public)

```php
setReflectedFiles(array $files)
```



### render (public)

```php
render()
```



# MultiDocRendererFactory

_class which is a factory for a renderer_

## Summary

### Public methods

createRenderer()

## Methods

### createRenderer (public)

```php
createRenderer(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Create a renderer by format identifiert

# MultiDocRendererFactoryDefinition

_class which is a factory definition for a renderer_

## Summary

### Public methods

__construct()
make()
getName()
getDescription()
getClassname()

## Properties

### name (protected)

```php
name: string
```

A short name for a renderer

### description (protected)

```php
description: string
```

A longer introduction for a renderer

### classname (protected)

```php
classname: string
```

The classname of the renderer to use

## Methods

### __construct (public)

```php
__construct(string $name, string $description, string $classname)
```

Constructor

### make (public)

```php
make(string $name, string $description, string $classname)
```

Create a new renderer definition

### getName (public)

```php
getName()
```

Returns the name of the renderer

### getDescription (public)

```php
getDescription()
```

Returns the description of the renderer

### getClassname (public)

```php
getClassname()
```

Returns the class name of the renderer

# MultiDocRendererFactoryDefinitionList

_class which is a list of factory definitions for a renderer_

## Summary

### Public methods

__construct()
findByIndex()
findByName()
existsByIndex()
existsByName()
getAllRegisteredRenderers()

### Private methods

initDefaultRenderers()
initCustomRenderers()
addRendererDefinition()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### rendererDefinitions (protected)

```php
rendererDefinitions: \horstoeko\multidocumentor\Renderer\MultiDocRendererFactoryDefinition[]
```

A List of defined renderers

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### findByIndex (public)

```php
findByIndex(int $index, bool $raiseExceptionIfNotFound = true)
```

Find a renderer by it&#039;s registerd $index

### findByName (public)

```php
findByName(string $name, bool $raiseExceptionIfNotFound = true)
```

Find renderer by it&#039;s $name

### existsByIndex (public)

```php
existsByIndex(int $index)
```

Check if a renderer has been registered on $index

### existsByName (public)

```php
existsByName(string $name)
```

Check if a renderer has been registered with $name

### getAllRegisteredRenderers (public)

```php
getAllRegisteredRenderers()
```

Returns a list of all registered renderers

### initDefaultRenderers (private)

```php
initDefaultRenderers()
```

Initialize a list of default renderers

### initCustomRenderers (private)

```php
initCustomRenderers()
```

Initialize custom renderers from config

### addRendererDefinition (private)

```php
addRendererDefinition(\horstoeko\multidocumentor\Renderer\MultiDocRendererFactoryDefinition $rendererDefinition)
```

Add a renderer definition to list

# MultiDocPdfFile

_Class which creates a pdf file_

## Summary

### Public methods

__construct()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

# MultiDocRendererMultiplePdf

_service class which renders the output documents as an single PDF document_

## Summary

### Public methods

__construct()
setReflectedFiles()
render()

### Private methods

renderSingleMarkDown()
renderClass()
renderInterface()
renderTrait()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### markupService (protected)

```php
markupService: \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
```



### reflectedFiles (protected)

```php
reflectedFiles
```

Files to handle

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### setReflectedFiles (public)

```php
setReflectedFiles(array $files)
```



### render (public)

```php
render()
```



### renderSingleMarkDown (private)

```php
renderSingleMarkDown(string $destinationFilename)
```

Render a single PDF file

### renderClass (private)

```php
renderClass(mixed $class)
```

Render a class pdf file

### renderInterface (private)

```php
renderInterface(mixed $interface)
```

Render a interface pdf file

### renderTrait (private)

```php
renderTrait(mixed $interface)
```

Render a interface pdf file

# MultiDocRendererSinglePdf

_service class which renders the output documents as an single PDF document_

## Summary

### Public methods

__construct()
setReflectedFiles()
render()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### markupService (protected)

```php
markupService: \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
```



### reflectedFiles (protected)

```php
reflectedFiles
```

Files to handle

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### setReflectedFiles (public)

```php
setReflectedFiles(array $files)
```



### render (public)

```php
render()
```



# MultiDocAbstractMarkupService

_Service class which renders the markup_

## Summary

### Public methods

__construct()
initializeService()
getMarkupTemplateDirectory()
getMarkupOutput()
addToMarkupOutput()
render()
renderAndAddToOutput()
writeHeader()
writeSummary()
writeConstants()
writeProperties()
writeMethods()
createFromClass()
createFromInterface()
createFromTrait()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### twigService (private)

```php
twigService: \horstoeko\multidocumentor\Interfaces\MultiDocTwigServiceInterface
```

The HTML Engine

### markup (private)

```php
markup: string
```

The internal markup container

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructur

### initializeService (public)

```php
initializeService()
```



### getMarkupTemplateDirectory (public)

```php
getMarkupTemplateDirectory()
```



### getMarkupOutput (public)

```php
getMarkupOutput()
```



### addToMarkupOutput (public)

```php
addToMarkupOutput(string $add)
```



### render (public)

```php
render(string $name, array $data = array())
```



### renderAndAddToOutput (public)

```php
renderAndAddToOutput(string $name, array $data = array())
```



### writeHeader (public)

```php
writeHeader(string $name, string $summary, string $description)
```



### writeSummary (public)

```php
writeSummary(array $constants, array $properties, array $methods)
```



### writeConstants (public)

```php
writeConstants(array $constants)
```



### writeProperties (public)

```php
writeProperties(array $properties)
```



### writeMethods (public)

```php
writeMethods(array $methods)
```



### createFromClass (public)

```php
createFromClass(\phpDocumentor\Reflection\Php\Class_ $class)
```



### createFromInterface (public)

```php
createFromInterface(\phpDocumentor\Reflection\Php\Interface_ $interface)
```



### createFromTrait (public)

```php
createFromTrait(\phpDocumentor\Reflection\Php\Trait_ $trait)
```



# MultiDocCreatorService

_Service class which will create the documentation_

## Summary

### Public methods

__construct()
render()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### finderService (protected)

```php
finderService: \horstoeko\multidocumentor\Interfaces\MultiDocFinderServiceInterface
```

Finder Service

### renderService (protected)

```php
renderService: \horstoeko\multidocumentor\Interfaces\MultiDocRenderServiceInterface
```

Render Service

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### render (public)

```php
render()
```



# MultiDocFinderService

_Service class which will give us all files to handle_

## Summary

### Public methods

__construct()
getAllFiles()
getAllFilesAsPhpDocLocalFiles()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### finder (protected)

```php
finder: \Symfony\Component\Finder\Finder
```

Internal finder component

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### getAllFiles (public)

```php
getAllFiles()
```



### getAllFilesAsPhpDocLocalFiles (public)

```php
getAllFilesAsPhpDocLocalFiles()
```



# MultiDocMarkupHtmlService

_Service class which renders the markup in HTML format_

## Summary

### Public methods

getMarkupTemplateDirectory()
writeHeader()
writeSummary()
writeConstants()
writeProperties()
writeMethods()
createFromClass()
createFromInterface()
createFromTrait()

## Methods

### getMarkupTemplateDirectory (public)

```php
getMarkupTemplateDirectory()
```



### writeHeader (public)

```php
writeHeader(string $name, string $summary, string $description)
```



### writeSummary (public)

```php
writeSummary(array $constants, array $properties, array $methods)
```



### writeConstants (public)

```php
writeConstants(array $constants)
```



### writeProperties (public)

```php
writeProperties(array $properties)
```



### writeMethods (public)

```php
writeMethods(array $methods)
```



### createFromClass (public)

```php
createFromClass(\phpDocumentor\Reflection\Php\Class_ $class)
```



### createFromInterface (public)

```php
createFromInterface(\phpDocumentor\Reflection\Php\Interface_ $interface)
```



### createFromTrait (public)

```php
createFromTrait(\phpDocumentor\Reflection\Php\Trait_ $trait)
```



# MultiDocMarkupMarkdownService

_Service class which renders the markup in markdown format_

## Summary

### Public methods

getMarkupTemplateDirectory()
writeHeader()
writeSummary()
writeConstants()
writeProperties()
writeMethods()
createFromClass()
createFromInterface()
createFromTrait()

## Methods

### getMarkupTemplateDirectory (public)

```php
getMarkupTemplateDirectory()
```



### writeHeader (public)

```php
writeHeader(string $name, string $summary, string $description)
```



### writeSummary (public)

```php
writeSummary(array $constants, array $properties, array $methods)
```



### writeConstants (public)

```php
writeConstants(array $constants)
```



### writeProperties (public)

```php
writeProperties(array $properties)
```



### writeMethods (public)

```php
writeMethods(array $methods)
```



### createFromClass (public)

```php
createFromClass(\phpDocumentor\Reflection\Php\Class_ $class)
```



### createFromInterface (public)

```php
createFromInterface(\phpDocumentor\Reflection\Php\Interface_ $interface)
```



### createFromTrait (public)

```php
createFromTrait(\phpDocumentor\Reflection\Php\Trait_ $trait)
```



# MultiDocRenderService

_Service class which will render the documentation_

## Summary

### Public methods

__construct()
setLocalFiles()
render()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### localFiles (protected)

```php
localFiles
```

Files to handle

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### setLocalFiles (public)

```php
setLocalFiles(array $files)
```



### render (public)

```php
render()
```



# MultiDocTwigService

_Service class which will render the twig templates_

## Summary

### Public methods

__construct()
addTemplateDirectory()
render()

## Properties

### config (protected)

```php
config: \horstoeko\multidocumentor\Config\MultiDocConfig
```

Configuration

### twigEngine (protected)

```php
twigEngine: \horstoeko\multidocumentor\Twig\MultiDocTwigEngine
```

The Twig engine

## Methods

### __construct (public)

```php
__construct(\horstoeko\multidocumentor\Config\MultiDocConfig $config)
```

Constructor

### addTemplateDirectory (public)

```php
addTemplateDirectory(string $directory)
```



### render (public)

```php
render(string $name, array $data)
```



# MultiDocTwigEngine

_Class which wraps base Twig Engine_

## Summary

### Public methods

__construct()
addTemplateDirectory()
render()

## Properties

### twigLoader (protected)

```php
twigLoader: \Twig\Loader\FilesystemLoader
```

Twig template loader

### twigEnvironment (protected)

```php
twigEnvironment: \Twig\Environment
```

Twig Environment

## Methods

### __construct (public)

```php
__construct()
```

Constructor

### addTemplateDirectory (public)

```php
addTemplateDirectory(string $directory)
```

Add a folder where templates are stored

### render (public)

```php
render(string $name, array $data)
```

Render a template

# MultiDocTwigExtension

_Class for multiDoc twig extensions_

## Summary

### Public methods

getFilters()
removeInvisibleCharacters()
parsedown()

## Methods

### getFilters (public)

```php
getFilters()
```

Returns a list of filters to add to the existing list.

### removeInvisibleCharacters (public)

```php
removeInvisibleCharacters(mixed $string)
```

Removes invisble Characters

### parsedown (public)

```php
parsedown(mixed $string)
```

Parse markdown to HTML

