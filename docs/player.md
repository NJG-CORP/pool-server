**Получить свой профиль**

**GET /players/self**
```
    None
```
```
    {
        player: {
            id: number,
            email: string,
            age: number,
            name: string,
            surname: string,
            gender: integer,
            avatar: {
                id: number,
                path: string
            },
            location: {
                id: number,
                city: {
                    id: number,
                    name: string
                },
                latitude: number,
                longitude: number,
                address: string
            },
            city: {
                id: number,
                name: string
            },
            status: boolean,
            created_at: date,
            updated_at: date
        }
    }
```

**Обновить свой профиль**

**POST /players/update**
```
    >age?
    >name?
    >surname?
    >gender?
    >city?{
        id!
        name!
    }
    >avatar?
```
```
    {
        player: {
            id: number,
            email: string,
            age: number,
            name: string,
            surname: string,
            gender: integer,
            avatar: {
                id: number,
                path: string
            },
            location: {
                id: number,
                city: {
                    id: number,
                    name: string
                },
                latitude: number,
                longitude: number,
                address: string
            },
            city: {
                id: number,
                name: string
            },
            status: boolean,
            created_at: date,
            updated_at: date
        }
    }
```

**Игроки на карте**

**GET /players/map**
```
    >city_id!
```
```
    {
        players: [
            {
                id: number,
                name: string,
                surname: string,
                avatar: {
                    id: number,
                    path: string
                },
                location: {
                    id: number,
                    city: {
                        id: number,
                        name: string
                    },
                    latitude: number,
                    longitude: number,
                    address: string
                },
                city: {
                    id: number,
                    name: string
                },
            }
        ]
    }
```

**Получить профиль игрока**

**GET /players/show/{PLAYER_ID}**
```
    None
```
```
    {
        player: {
            id: number,
            email: string,
            age: number,
            name: string,
            surname: string,
            gender: integer,
            avatar: {
                id: number,
                path: string
            },
            location: {
                id: number,
                city: {
                    id: number,
                    name: string
                },
                latitude: number,
                longitude: number,
                address: string
            },
            city: {
                id: number,
                name: string
            },
            status: boolean,
            created_at: date,
            updated_at: date
        }
    }
```

**Поиск игроков**

**GET /players/search**
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
                avatar: {
                    id: number,
                    path: string
                }
            }
        ]
    }
```



