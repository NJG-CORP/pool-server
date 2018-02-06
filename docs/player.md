**Получить свой профиль**

**GET /players/self**
```
    None
```
```
    {
        player: Player
    }
```

**Игроки на карте**

**GET /players/map**
```
    >city_id!
```
```
    {
        players: Player[]
    }
```

**Получить профиль игрока**

**GET /players/show/{PLAYER_ID}**
```
    None
```
```
    {
        player: Player
    }
```

**Поиск игроков**

**GET /players/search**
```
    None
```
```
    {
        players: Player[]
    }
```



