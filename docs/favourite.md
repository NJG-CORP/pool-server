**Список избранных**

**GET /favourites/list**
```
    None
```
```
    {
        players: [
            {
                id: number,
                name: string, 
                surname: string,
                status: boolean,
                avatar: {
                    id: number,
                    path: string
                }
            }
        ]
    }
```

**Добавить игрока в избранное**

**POST /favourites/add/{PLAYER_ID}**
```
    None
```
```
    {
        player: { //добавленный игрок
            id: number,
            name: string, 
            surname: string,
            status: boolean
        }
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



