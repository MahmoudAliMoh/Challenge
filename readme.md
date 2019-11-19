
## Challenge Idea
We have two providers collect data from them in json files we need to read and make some filter operations on them to get the result

You can check the json files inside jsons folder 

- `DataProviderX` data is stored in [DataProviderX.json]
- `DataProviderY` data is stored in [DataProviderX.json]


`DataProviderX  schema is 
```
{
  parentAmount:200,
  Currency:'USD',
  parentEmail:'parent1@parent.eu',
  statusCode:1,
  registerationDate: '2018-11-30',
  parentIdentification: 'd3d29d70-1d25-11e3-8591-034165a3a613'
}
```

we have three status for `DataProviderX` 

- `authorised` which will have statusCode `1`
- `decline` which will have statusCode `2`
- `refunded` which will have statusCode `3`


`DataProviderY  schema is 
```
{
  balance:300,
  currency:'AED',
  email:'parent2@parent.eu',
  status:100,
  created_at: '22/12/2018',
  id: '4fc2-a8d1'
}
```

we have three status for `DataProviderY` 

- `authorised` which will have statusCode `100`
- `decline` which will have statusCode `200`
- `refunded` which will have statusCode `300`
