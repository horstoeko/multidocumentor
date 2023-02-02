# MultiDocConfig

_Class representing the MultiDoc Configuration_

## Summary

### Public methods

__construct
getIncludeDirectories
setIncludeDirectories
addIncludeDirectory
getExcludeDirectories
setExcludeDirectories
addExcludeDirectory
getOutputTo
setOutputTo
getOutputFormat
setOutputFormat
getAssetDirectory
setAssetDirectory
getHtmlDirectory
setHtmlDirectory
getMarkdownDirectory
setMarkdownDirectory
getFontsDirectory
setFontsDirectory

## Constants
                                    
### BAUM

```php
BAUM
```

Baum
Baumtets
                                    
### TEST

```php
TEST
```

Test
TestTest

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
outputFormat: int
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
setOutputFormat(int $outputFormat)
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

