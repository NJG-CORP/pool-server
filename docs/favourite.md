**Список избранных**

**GET /favourite/list**
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

**POST /favourite/add/{PLAYER_ID}**
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

**POST /favourite/remove/{PLAYER_ID}**
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



