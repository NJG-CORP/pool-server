**Список словарей**

**GET /taxonomies/vocabularies**
```
    None
```
```
    {
        vocabularies: [
            {
                id: number,
                name: string
            }
        ]
    }
```

**Список значений словаря**

**POST /taxonomies/terms/{VOCABULARY_ID}**
```
    None
```
```
    {
        terms: [
            {
                id: number,
                vocabulary_id: number,
                name: string
            }
        ]
    }
```


