**Список избранных**

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

**Добавить игрока в избранное**

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

**Убрать игрока из избранного**

**POST /favourites/remove/{PLAYER_ID}**
```
    None
```
```
    {
        player: { //удаленный игрок
                id: number,
                name: string, 
                surname: string,
                status: boolean
            }
    }
```



