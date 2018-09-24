# Hierarchy API
Hierarchy api accepts a JSON input with a flat structure and transforms it to a tree-like structure to visualize it easily

  - Input must be a valid JSON format of the "flat" structure
  - Output will be a valid JSON format of the transformed tree-like structure

### API Calls
| Method | URL | Body Content|
| ------ | ------ | ---------- |
| POST | /api/hierarchy | data: JsonString |


### Sample Responses
For a valid input (responds with 200)
```sh
{"result":{"Jonas":[{"Sophie":[{"Nick":[{"Pete":[]},{"Barbara":[]}]}]}]}}
```

For no data input (responds with 422)
```sh
{"errors":[{"data":["The data field is required."]}]}
```

For invalid JSON (responds with 422)
```sh
{"errors":["JSON input is not valid"]}
```

For an input that contains loop (responds with 422)
```sh
{"errors":["Loop occurred in the input"]}
```

