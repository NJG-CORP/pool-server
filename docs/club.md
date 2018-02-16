**Список клубов**

**GET /clubs/list**
```
    None
```
```
    {
        clubs: [
            {
                id: number,
                name: string,
                description: string,
                location: {
                    id: number,
                    latitude: number,
                    longitude: number,
                    city: {
                        id: number,
                        name: string
                    },
                    address: string
                }
            }
        ]
    }
```

**Клуб**

**GET /clubs/{CLUB_ID}**
```
    None
```
```
    {
        club:{
            id: number,
            name: string,
            description: string,
            location: {
                id: number,
                latitude: number,
                longitude: number,
                city: {
                    id: number,
                    name: string
                },
                address: string
            }
        }
    }
```



