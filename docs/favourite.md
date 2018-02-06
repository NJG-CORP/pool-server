**Список избранных**

**GET /favourite/list**
```
    None
```
```
    {
        players: Player[]
    }
```

**Добавить игрока в избранное**

**POST /favourite/add/{PLAYER_ID}**
```
    None
```
```
    {
        player: Player //Добавленный игрок
    }
```

**Убрать игрока из избранного**

**POST /favourite/remove/{PLAYER_ID}**
```
    None
```
```
    {
        player: Player //Удаленный игрок
    }
```



