(()=>{"use strict";var L,M={993:()=>{const L=window.React,M=window.wp.blocks,j=window.wp.i18n,u=window.wp.blockEditor,N=window.wp.components;function y(L,M=!1){let j="",u=["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0","é","è",",","ç","à","ù",".","ô","î","ê","û","ö","ï","ë","ü","â","ä","€","$","£","¥","%","&","Â","Ä","Ê","Ë","Î","Ï","Ö","Ô","Û","Ü","Ù","À","Ç","É","È","Æ","Œ","œ","Å","Ø","Þ","ð","Ý","ý","þ","ÿ","ß","Ÿ","Š","š","Ž","ž"," "];M&&(u.push("/"),u.push(":"));for(let M=0;M<L.length;M++){if("<"===L[M])for(;">"!==L[M]&&M<L.length;)M++;u.includes(L[M])&&(j+=L[M])}return j}const S=JSON.parse('{"UU":"create-block/simple-form-block"}'),I=(0,L.createElement)("img",{src:"data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyBpZD0iTGF5ZXJfMiIgZGF0YS1uYW1lPSJMYXllciAyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2aWV3Qm94PSIwIDAgMzU0LjEyIDM1NC4xMiI+CiAgPGRlZnM+CiAgICA8bGluZWFyR3JhZGllbnQgaWQ9ImxpbmVhci1ncmFkaWVudCIgeDE9IjEwNi45NyIgeTE9IjMxNi4xOSIgeDI9IjEwNi45NyIgeTI9IjI4NS43NyIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPgogICAgICA8c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiNmYmMxMGYiLz4KICAgICAgPHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjZjM5NDFjIi8+CiAgICA8L2xpbmVhckdyYWRpZW50PgogICAgPGxpbmVhckdyYWRpZW50IGlkPSJsaW5lYXItZ3JhZGllbnQtMiIgeDE9IjE3My45NSIgeTE9IjMxNi41MSIgeDI9IjE3My45NSIgeTI9IjI4NS44NiIgeGxpbms6aHJlZj0iI2xpbmVhci1ncmFkaWVudCIvPgogICAgPGxpbmVhckdyYWRpZW50IGlkPSJsaW5lYXItZ3JhZGllbnQtMyIgeDE9IjE0NS4yNyIgeTE9IjMxNi4wMyIgeDI9IjE0NS4yNyIgeTI9IjI4NS44IiB4bGluazpocmVmPSIjbGluZWFyLWdyYWRpZW50Ii8+CiAgICA8bGluZWFyR3JhZGllbnQgaWQ9ImxpbmVhci1ncmFkaWVudC00IiB4MT0iMjAyLjUxIiB5MT0iMzE2LjU1IiB4Mj0iMjAyLjUxIiB5Mj0iMjg1LjIzIiB4bGluazpocmVmPSIjbGluZWFyLWdyYWRpZW50Ii8+CiAgICA8bGluZWFyR3JhZGllbnQgaWQ9ImxpbmVhci1ncmFkaWVudC01IiB4MT0iMjI3Ljc5IiB5MT0iMzE2LjU5IiB4Mj0iMjI3Ljc5IiB5Mj0iMjg1LjMiIHhsaW5rOmhyZWY9IiNsaW5lYXItZ3JhZGllbnQiLz4KICAgIDxsaW5lYXJHcmFkaWVudCBpZD0ibGluZWFyLWdyYWRpZW50LTYiIHgxPSIyNTUuMyIgeTE9IjMxNi41OCIgeDI9IjI1NS4zIiB5Mj0iMjg1LjE3IiB4bGluazpocmVmPSIjbGluZWFyLWdyYWRpZW50Ii8+CiAgICA8bGluZWFyR3JhZGllbnQgaWQ9ImxpbmVhci1ncmFkaWVudC03IiB4MT0iMTc2Ljg5IiB5MT0iMjMuMDYiIHgyPSIxNzYuODkiIHkyPSIyNzQuNTMiIHhsaW5rOmhyZWY9IiNsaW5lYXItZ3JhZGllbnQiLz4KICA8L2RlZnM+CiAgPGcgaWQ9IkxheWVyXzEtMiIgZGF0YS1uYW1lPSJMYXllciAxIj4KICAgIDxnPgogICAgICA8cmVjdCB3aWR0aD0iMzU0LjEyIiBoZWlnaHQ9IjM1NC4xMiIgcng9IjUzLjEyIiByeT0iNTMuMTIiIGZpbGw9IiNmZmYiIHN0cm9rZS13aWR0aD0iMCIvPgogICAgICA8cGF0aCBkPSJNMTA0Ljc4LDI5OC40OWMtMS4yOSw0LjQ2LTIuNDgsOC42MS0zLjY4LDEyLjc3LS40MywxLjQ4LS45LDIuOTQtMS4yNyw0LjQzLS4yLjc5LS42LDEuMDctMS4zOSwxLjA0LTEuNTktLjA0LTMuMTktLjA2LTQuNzgsMC0uODMuMDMtMS4xMy0uMzYtMS4zOC0xLjA3LTMuMTctOS4xOC02LjM2LTE4LjM2LTkuNTktMjcuNTMtLjQtMS4xNC0uMjYtMS41NiwxLjA1LTEuNSwxLjg2LjA4LDMuNzQuMDgsNS42LS4wNCwxLjAyLS4wNiwxLjQ3LjI0LDEuNzUsMS4yMSwxLjY1LDUuODEsMy43MywxMS40OSw1LjIsMTcuMzUuMDQuMTYuMTEuMy4yNC42NSwxLjIzLTMuMDcsMS42Mi02LjIsMi40Ny05LjE5LjgzLTIuOSwxLjYtNS44MiwyLjMzLTguNzUuMjMtLjkyLjYzLTEuMjgsMS41OC0xLjIzLDEuOC4wOSwzLjk3LS41Miw1LjMyLjI5LDEuMzcuODIsMS4zLDMuMjIsMS44NSw0LjkyLDEuNDUsNC40OSwyLjgsOSw0LjIxLDEzLjU1LjgzLS4zOC43Ni0xLjE3LjkxLTEuODEsMS4yMi01LjI0LDIuNTEtMTAuNDUsMy45MS0xNS42NS4yNy0xLjAxLjctMS4zMywxLjY4LTEuMzEsMS44MS4wMywzLjYzLS4wMiw1LjQ0LS4wOSwxLjAyLS4wNCwxLjQuMTgsMS4wMiwxLjMxLTMuMSw5LjIxLTYuMTYsMTguNDQtOS4xOSwyNy42Ny0uMzIuOTYtLjgzLDEuMzEtMS44MSwxLjIzLTEuOC0uMTUtMy45Ny42Ni01LjMyLS4zLTEuMzUtLjk2LTEuMzUtMy4yNC0xLjkyLTQuOTUtMS40MS00LjIxLTIuNzUtOC40NS00LjIyLTEzWiIgZmlsbD0iIzEzMGEwNiIgc3Ryb2tlLXdpZHRoPSIwIi8+CiAgICAgIDxwYXRoIGQ9Ik0xODQuNzIsMjk4LjQzYy0uMDgsMi4zMi4zOCw1LjgxLS4xNyw5LjI0LTEuMDEsNi4zMi01LjU4LDkuNzctMTIuNTMsOS41Ny00Ljc0LS4xNC04LjkyLTEuNDItMTEuMTgtNi4xMi0uNzctMS42LTEuMTctMy4zMy0xLjE4LTUuMS0uMDQtNS45OSwwLTExLjk4LS4wNC0xNy45NywwLTEuMDYuMy0xLjQ3LDEuNC0xLjQyLDEuNTMuMDcsMy4wOC4wNiw0LjYxLS4wMywxLjExLS4wNywxLjQuMzUsMS4zOSwxLjQxLS4wNSw1LjE2LS4wNCwxMC4zMy0uMDMsMTUuNSwwLC45My4wMiwxLjg3LjE1LDIuNzkuNTYsMy44OCw0LjI2LDUuNzUsNy43NywzLjk0LDEuMzctLjcsMi4wNy0xLjg5LDIuMjktMy40MS40My0zLjAxLjE5LTYuMDMuMjMtOS4wNS4wNC0zLjI0LjA1LTYuNDgsMC05LjczLS4wMS0xLjAzLjI0LTEuNDksMS4zNy0xLjQzLDEuNTMuMDgsMy4wOC4wNSw0LjYxLS4wMi45OS0uMDUsMS4zNS4yNywxLjMzLDEuMy0uMDYsMy4xMy0uMDIsNi4yNi0uMDIsMTAuNTNaIiBmaWxsPSIjMTMwYTA2IiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgPHBhdGggZD0iTTEzMS43NCwzMDEuNTRjMC00LjQ1LjA0LTguOS0uMDItMTMuMzUtLjAyLTEuMTkuMjctMS42NSwxLjU0LTEuNjMsNC4zOS4xLDguNzktLjIsMTMuMTguMTQsNC4wNS4zMiw3LjM0LDMuMDIsOC40Myw2LjgsMS4yNiw0LjM3LjA3LDguNTUtMy4xMiwxMS4wMi0xLjUyLDEuMTgtMy4yOSwxLjc4LTUuMTcsMS45NS0yLjAyLjE5LTQuMDYuMjctNi4wOC4yNi0xLjE5LDAtMS42Ni4zMy0xLjYsMS42LjEsMi4zNiwwLDQuNzIuMDUsNy4wOC4wMywxLjAzLS4zNiwxLjM1LTEuMzUsMS4zMS0xLjM3LS4wNi0yLjc1LS4wOS00LjEyLjAyLTEuMzQuMTEtMS44LS4yOC0xLjc3LTEuNy4wOS00LjUuMDMtOS4wMS4wMy0xMy41MVpNMTM4Ljk0LDI5Ni43NGgwYzAsMS4wNC4wMiwyLjA4LDAsMy4xMi0uMDIuNzMuMiwxLjE1LDEuMDEsMS4xMiwxLjQ4LS4wNSwyLjk2LjAzLDQuNDMtLjEzLDIuMjgtLjI1LDMuNjEtMS43MiwzLjcxLTMuODguMTEtMi4zOC0xLjExLTMuOTYtMy40NC00LjQ2LTEuNTctLjM0LTMuMTYtLjEyLTQuNzQtLjItLjgzLS4wNC0xLC40Mi0uOTcsMS4xNC4wNCwxLjA5LDAsMi4xOSwwLDMuMjhaIiBmaWxsPSIjMTMwYTA2IiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgPHBhdGggZD0iTTIwMC42MiwyODUuOTdjMy40Mi4wMyw2LjU3Ljc1LDkuMjUsMi45OC44OC43MywxLjA5LDEuMzguMzQsMi4zNC0uNzMuOTUtMS4xMywyLjQxLTIuMDYsMi44OS0xLjEzLjU5LTItLjk5LTMuMDktMS40My0xLjc2LS43MS0zLjUyLTEuMjktNS40My0uOTYtMS40Ni4yNS0yLjU5LDEuMDEtMi44NSwyLjU4LS4yNiwxLjYxLjc4LDIuNDUsMi4wOSwyLjk1LDIuMTUuODIsNC4zNywxLjQ4LDYuNTEsMi4zNCw1LjYsMi4yNSw3LjgzLDcuNDksNS4zNiwxMi41Mi0xLjQxLDIuODctMy45Niw0LjIyLTYuOTMsNC43Ni00LjUxLjgxLTguODQuMjEtMTIuOTUtMS45NC0xLjEtLjU4LTEuMjgtMS4xMS0uNjUtMi4xNS42NS0xLjA4LDEuMjItMi4yMiwxLjcyLTMuMzcuNDktMS4xMi45NC0uOTgsMS43OC0uMjgsMi40MywyLDUuMjMsMi43Miw4LjMyLDEuOTYsMS4yNy0uMzEsMi40Mi0uODUsMi41My0yLjQyLjExLTEuNTQtLjg5LTIuMzMtMi4wOS0yLjgzLTEuODctLjc4LTMuODEtMS40LTUuNzEtMi4xMS0zLjQ1LTEuMjktNi4yNS0zLjE0LTYuODMtNy4yMy0uNzgtNS40OSwyLjctOS44Miw4LjYtMTAuNDguNzEtLjA4LDEuNDMtLjA3LDIuMS0uMVoiIGZpbGw9IiMxMzBhMDYiIHN0cm9rZS13aWR0aD0iMCIvPgogICAgICA8cGF0aCBkPSJNMjI2LjA4LDI4Ni4wNWMzLjM0LS4wNSw2LjQuNzksOS4wNiwyLjg4LjgzLjY1LDEuMjUsMS4yNy40NCwyLjMtLjc0Ljk0LTEuMDksMi40MS0yLjAyLDIuOTMtMS4xNC42NC0xLjk5LS45NC0zLjA1LTEuNC0xLjQ5LS42NC0zLjAyLS45OS00LjYyLTEuMDgtLjk2LS4wNS0xLjg1LjI0LTIuNjMuNzktMS44MiwxLjMtMS43MiwzLjQ2LjIzLDQuNTQsMS43OSwxLDMuODEsMS4zOSw1LjcxLDIuMDgsMS45My43LDMuNzYsMS41NSw1LjIzLDMuMDIsNC40Nyw0LjUsMi43LDEyLjEzLTMuMzksMTQuMjEtNS4yMSwxLjc4LTEwLjI3LDEuMDYtMTUuMTEtMS41LS44My0uNDQtLjk3LS44NS0uNTEtMS42Ni42NC0xLjE1LDEuMjEtMi4zNCwxLjcyLTMuNTUuNDYtMS4xLjg1LTEuMjQsMS44NC0uMzksMi40LDIuMDYsNS4yMywyLjY4LDguMzEsMS45MiwxLjE1LS4yOSwyLjIzLS43NywyLjQtMi4xNS4xOC0xLjQ3LS42Ni0yLjQxLTEuODctMy0xLjE4LS41Ny0yLjQ0LS45OS0zLjY2LTEuNDYtMS4wMi0uMzktMi4wNi0uNzYtMy4wOC0xLjE2LTQuNTItMS43NS02LjQxLTQuOTEtNS44My05LjUxLjU4LTQuNTcsMy45My03LjM4LDguODQtNy44MS42NS0uMDYsMS4zMiwwLDEuOTcsMFoiIGZpbGw9IiMxMzBhMDYiIHN0cm9rZS13aWR0aD0iMCIvPgogICAgICA8cGF0aCBkPSJNMjQwLjQ0LDMwMS43MmMtLjAxLTYuMjIsMi4zOC0xMS4xOSw4LjA4LTEzLjk5LDUuNTctMi43MywxMS4xNC0yLjQ1LDE2LjM5LDEuMTMuMjcuMTkuNTIuNC43OC42LDEuMDkuODMsMS41LDEuNzIuMjYsMi43Ni0uMDQuMDMtLjA3LjA5LS4xLjEzLS43Ni45My0xLjM1LDIuMjQtMi4zMywyLjcxLTEuMjQuNTktMS44Ny0xLjEzLTIuOTMtMS42Mi0yLjk5LTEuNC01Ljk0LTEuNjYtOC44LjEzLTMuMTEsMS45NS00LjEyLDUuMDUtMy45OSw4LjUyLjEyLDMuNDEsMS4yMiw2LjQ2LDQuNjEsNy45MywzLjQ5LDEuNTEsNi44MS44Myw5LjY3LTEuNjMuOTYtLjgyLDEuNC0uNjgsMi4wMi4yNi42LjkxLDEuMjgsMS43OSwyLjAyLDIuNi44NS45My43OSwxLjU4LS4yMiwyLjM5LTUuMzgsNC4zMS0xMS4zMSw0LjYzLTE3LjM0LDEuOTktNS42MS0yLjQ2LTcuOTItNy4yOS04LjEtMTMuMjUsMC0uMjIsMC0uNDQsMC0uNjZaIiBmaWxsPSIjMTMwYTA2IiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgPHBhdGggZD0iTTEwNi44MSwyOTcuNzZjLTEuMjksNC40Ni0yLjQ4LDguNjEtMy42OCwxMi43Ny0uNDMsMS40OC0uOSwyLjk0LTEuMjcsNC40My0uMi43OS0uNiwxLjA3LTEuMzksMS4wNC0xLjU5LS4wNC0zLjE5LS4wNi00Ljc4LDAtLjgzLjAzLTEuMTMtLjM2LTEuMzgtMS4wNy0zLjE3LTkuMTgtNi4zNi0xOC4zNi05LjU5LTI3LjUzLS40LTEuMTQtLjI2LTEuNTYsMS4wNS0xLjUsMS44Ni4wOCwzLjc0LjA4LDUuNi0uMDQsMS4wMi0uMDYsMS40Ny4yNCwxLjc1LDEuMjEsMS42NSw1LjgxLDMuNzMsMTEuNDksNS4yLDE3LjM1LjA0LjE2LjExLjMuMjQuNjUsMS4yMy0zLjA3LDEuNjItNi4yLDIuNDctOS4xOS44My0yLjksMS42LTUuODIsMi4zMy04Ljc1LjIzLS45Mi42My0xLjI4LDEuNTgtMS4yMywxLjguMDksMy45Ny0uNTIsNS4zMi4yOSwxLjM3LjgyLDEuMywzLjIyLDEuODUsNC45MiwxLjQ1LDQuNDksMi44LDksNC4yMSwxMy41NS44My0uMzguNzYtMS4xNy45MS0xLjgxLDEuMjItNS4yNCwyLjUxLTEwLjQ1LDMuOTEtMTUuNjUuMjctMS4wMS43LTEuMzMsMS42OC0xLjMxLDEuODEuMDMsMy42My0uMDIsNS40NC0uMDksMS4wMi0uMDQsMS40LjE4LDEuMDIsMS4zMS0zLjEsOS4yMS02LjE2LDE4LjQ0LTkuMTksMjcuNjctLjMyLjk2LS44MywxLjMxLTEuODEsMS4yMy0xLjgtLjE1LTMuOTcuNjYtNS4zMi0uMy0xLjM1LS45Ni0xLjM1LTMuMjQtMS45Mi00Ljk1LTEuNDEtNC4yMS0yLjc1LTguNDUtNC4yMi0xM1oiIGZpbGw9InVybCgjbGluZWFyLWdyYWRpZW50KSIgc3Ryb2tlLXdpZHRoPSIwIi8+CiAgICAgIDxwYXRoIGQ9Ik0xODYuNDUsMjk3LjdjLS4wOCwyLjMyLjM4LDUuODEtLjE3LDkuMjQtMS4wMSw2LjMyLTUuNTgsOS43Ny0xMi41Myw5LjU3LTQuNzQtLjE0LTguOTItMS40Mi0xMS4xOC02LjEyLS43Ny0xLjYtMS4xNy0zLjMzLTEuMTgtNS4xLS4wNC01Ljk5LDAtMTEuOTgtLjA0LTE3Ljk3LDAtMS4wNi4zLTEuNDcsMS40LTEuNDIsMS41My4wNywzLjA4LjA2LDQuNjEtLjAzLDEuMTEtLjA3LDEuNC4zNSwxLjM5LDEuNDEtLjA1LDUuMTYtLjA0LDEwLjMzLS4wMywxNS41LDAsLjkzLjAyLDEuODcuMTUsMi43OS41NiwzLjg4LDQuMjYsNS43NSw3Ljc3LDMuOTQsMS4zNy0uNywyLjA3LTEuODksMi4yOS0zLjQxLjQzLTMuMDEuMTktNi4wMy4yMy05LjA1LjA0LTMuMjQuMDUtNi40OCwwLTkuNzMtLjAxLTEuMDMuMjQtMS40OSwxLjM3LTEuNDMsMS41My4wOCwzLjA4LjA1LDQuNjEtLjAyLjk5LS4wNSwxLjM1LjI3LDEuMzMsMS4zLS4wNiwzLjEzLS4wMiw2LjI2LS4wMiwxMC41M1oiIGZpbGw9InVybCgjbGluZWFyLWdyYWRpZW50LTIpIiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgPHBhdGggZD0iTTEzMy40NywzMDAuOGMwLTQuNDUuMDQtOC45LS4wMi0xMy4zNS0uMDItMS4xOS4yNy0xLjY1LDEuNTQtMS42Myw0LjM5LjEsOC43OS0uMiwxMy4xOC4xNCw0LjA1LjMyLDcuMzQsMy4wMiw4LjQzLDYuOCwxLjI2LDQuMzcuMDcsOC41NS0zLjEyLDExLjAyLTEuNTIsMS4xOC0zLjI5LDEuNzgtNS4xNywxLjk1LTIuMDIuMTktNC4wNi4yNy02LjA4LjI2LTEuMTksMC0xLjY2LjMzLTEuNiwxLjYuMSwyLjM2LDAsNC43Mi4wNSw3LjA4LjAzLDEuMDMtLjM2LDEuMzUtMS4zNSwxLjMxLTEuMzctLjA2LTIuNzUtLjA5LTQuMTIuMDItMS4zNC4xMS0xLjgtLjI4LTEuNzctMS43LjA5LTQuNS4wMy05LjAxLjAzLTEzLjUxWk0xNDAuNjcsMjk2aDBjMCwxLjA0LjAyLDIuMDgsMCwzLjEyLS4wMi43My4yLDEuMTUsMS4wMSwxLjEyLDEuNDgtLjA1LDIuOTYuMDMsNC40My0uMTMsMi4yOC0uMjUsMy42MS0xLjcyLDMuNzEtMy44OC4xMS0yLjM4LTEuMTEtMy45Ni0zLjQ0LTQuNDYtMS41Ny0uMzQtMy4xNi0uMTItNC43NC0uMi0uODMtLjA0LTEsLjQyLS45NywxLjE0LjA0LDEuMDksMCwyLjE5LDAsMy4yOFoiIGZpbGw9InVybCgjbGluZWFyLWdyYWRpZW50LTMpIiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgPHBhdGggZD0iTTIwMi4zNiwyODUuMjNjMy40Mi4wMyw2LjU3Ljc1LDkuMjUsMi45OC44OC43MywxLjA5LDEuMzguMzQsMi4zNC0uNzMuOTUtMS4xMywyLjQxLTIuMDYsMi44OS0xLjEzLjU5LTItLjk5LTMuMDktMS40My0xLjc2LS43MS0zLjUyLTEuMjktNS40My0uOTYtMS40Ni4yNS0yLjU5LDEuMDEtMi44NSwyLjU4LS4yNiwxLjYxLjc4LDIuNDUsMi4wOSwyLjk1LDIuMTUuODIsNC4zNywxLjQ4LDYuNTEsMi4zNCw1LjYsMi4yNSw3LjgzLDcuNDksNS4zNiwxMi41Mi0xLjQxLDIuODctMy45Niw0LjIyLTYuOTMsNC43Ni00LjUxLjgxLTguODQuMjEtMTIuOTUtMS45NC0xLjEtLjU4LTEuMjgtMS4xMS0uNjUtMi4xNS42NS0xLjA4LDEuMjItMi4yMiwxLjcyLTMuMzcuNDktMS4xMi45NC0uOTgsMS43OC0uMjgsMi40MywyLDUuMjMsMi43Miw4LjMyLDEuOTYsMS4yNy0uMzEsMi40Mi0uODUsMi41My0yLjQyLjExLTEuNTQtLjg5LTIuMzMtMi4wOS0yLjgzLTEuODctLjc4LTMuODEtMS40LTUuNzEtMi4xMS0zLjQ1LTEuMjktNi4yNS0zLjE0LTYuODMtNy4yMy0uNzgtNS40OSwyLjctOS44Miw4LjYtMTAuNDguNzEtLjA4LDEuNDMtLjA3LDIuMS0uMVoiIGZpbGw9InVybCgjbGluZWFyLWdyYWRpZW50LTQpIiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgPHBhdGggZD0iTTIyNy44MiwyODUuMzJjMy4zNC0uMDUsNi40Ljc5LDkuMDYsMi44OC44My42NSwxLjI1LDEuMjcuNDQsMi4zLS43NC45NC0xLjA5LDIuNDEtMi4wMiwyLjkzLTEuMTQuNjQtMS45OS0uOTQtMy4wNS0xLjQtMS40OS0uNjQtMy4wMi0uOTktNC42Mi0xLjA4LS45Ni0uMDUtMS44NS4yNC0yLjYzLjc5LTEuODIsMS4zLTEuNzIsMy40Ni4yMyw0LjU0LDEuNzksMSwzLjgxLDEuMzksNS43MSwyLjA4LDEuOTMuNywzLjc2LDEuNTUsNS4yMywzLjAyLDQuNDcsNC41LDIuNywxMi4xMy0zLjM5LDE0LjIxLTUuMjEsMS43OC0xMC4yNywxLjA2LTE1LjExLTEuNS0uODMtLjQ0LS45Ny0uODUtLjUxLTEuNjYuNjQtMS4xNSwxLjIxLTIuMzQsMS43Mi0zLjU1LjQ2LTEuMS44NS0xLjI0LDEuODQtLjM5LDIuNCwyLjA2LDUuMjMsMi42OCw4LjMxLDEuOTIsMS4xNS0uMjksMi4yMy0uNzcsMi40LTIuMTUuMTgtMS40Ny0uNjYtMi40MS0xLjg3LTMtMS4xOC0uNTctMi40NC0uOTktMy42Ni0xLjQ2LTEuMDItLjM5LTIuMDYtLjc2LTMuMDgtMS4xNi00LjUyLTEuNzUtNi40MS00LjkxLTUuODMtOS41MS41OC00LjU3LDMuOTMtNy4zOCw4Ljg0LTcuODEuNjUtLjA2LDEuMzIsMCwxLjk3LDBaIiBmaWxsPSJ1cmwoI2xpbmVhci1ncmFkaWVudC01KSIgc3Ryb2tlLXdpZHRoPSIwIi8+CiAgICAgIDxwYXRoIGQ9Ik0yNDIuMTcsMzAwLjk5Yy0uMDEtNi4yMiwyLjM4LTExLjE5LDguMDgtMTMuOTksNS41Ny0yLjczLDExLjE0LTIuNDUsMTYuMzksMS4xMy4yNy4xOS41Mi40Ljc4LjYsMS4wOS44MywxLjUsMS43Mi4yNiwyLjc2LS4wNC4wMy0uMDcuMDktLjEuMTMtLjc2LjkzLTEuMzUsMi4yNC0yLjMzLDIuNzEtMS4yNC41OS0xLjg3LTEuMTMtMi45My0xLjYyLTIuOTktMS40LTUuOTQtMS42Ni04LjguMTMtMy4xMSwxLjk1LTQuMTIsNS4wNS0zLjk5LDguNTIuMTIsMy40MSwxLjIyLDYuNDYsNC42MSw3LjkzLDMuNDksMS41MSw2LjgxLjgzLDkuNjctMS42My45Ni0uODIsMS40LS42OCwyLjAyLjI2LjYuOTEsMS4yOCwxLjc5LDIuMDIsMi42Ljg1LjkzLjc5LDEuNTgtLjIyLDIuMzktNS4zOCw0LjMxLTExLjMxLDQuNjMtMTcuMzQsMS45OS01LjYxLTIuNDYtNy45Mi03LjI5LTguMS0xMy4yNSwwLS4yMiwwLS40NCwwLS42NloiIGZpbGw9InVybCgjbGluZWFyLWdyYWRpZW50LTYpIiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgPGc+CiAgICAgICAgPGcgb3BhY2l0eT0iLjc3Ij4KICAgICAgICAgIDxwYXRoIGQ9Ik0yNjQuMDksMTI5LjQ5Yy4yMi0xMS4yNS0uMTItMjIuNS4wNS0zMy43NC4wMi0yLjMzLS42NS0yLjg0LTIuODktMi44MS0xMi41Ny4xMi0yNS4xNC4wMi0zNy43MS4xMy0yLjEyLjAyLTIuNy0uNTUtMi43Mi0yLjY4LDExLjE1LTg3LjQ2LTEwMy43Ni04Ny4xOS05My4zMy0uMTQuMDMsMi4zNC0uNzMsMi44NC0yLjk1LDIuODItMTIuMzYtLjExLTI0LjczLS4wMi0zNy4xLS4xLTIuMDksMC0yLjk1LjI5LTIuOTQsMi43NS4wNyw2MC4yLjA1LDEyMC40MS4wNSwxODAuNjIsNTguOTgtLjAzLDExNy45Ny0uMDYsMTc2Ljk1LDAsMi42OCwwLDIuNi0xLjE1LDIuNi0zLjA0LS4wNC00Ny45NC0uMDQtOTUuODgtLjA0LTE0My44MVpNMTQyLjQzLDY2Ljc1YzEwLjUyLTQ0LjA4LDcyLjM1LTM2LjM3LDY0LjA5LDIzLjk4LjA0LDEuOTQtLjcyLDIuMzQtMi40OSwyLjMyLTEwLjAzLS4wNy0yMC4wNi0uMDMtMzAuMDgtLjAzaDBjLTkuODMsMC0xOS42NS0uMDUtMjkuNDguMDUtMi4xLjAzLTIuODEtLjUxLTIuNy0yLjY4LjQxLTcuODgtLjYxLTE1LjguNjYtMjMuNjVaIiBmaWxsPSIjMTMwYTA2IiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgICA8L2c+CiAgICAgICAgPHBhdGggZD0iTTI2Ni42NCwxMjcuNjdjLjIyLTExLjI1LS4xMi0yMi41LjA1LTMzLjc0LjAyLTIuMzMtLjY1LTIuODQtMi44OS0yLjgxLTEyLjU3LjEyLTI1LjE0LjAyLTM3LjcxLjEzLTIuMTIuMDItMi43LS41NS0yLjcyLTIuNjgsMTEuMTUtODcuNDYtMTAzLjc2LTg3LjE5LTkzLjMzLS4xNC4wMywyLjM0LS43MywyLjg0LTIuOTUsMi44Mi0xMi4zNi0uMTEtMjQuNzMtLjAyLTM3LjEtLjEtMi4wOSwwLTIuOTUuMjktMi45NCwyLjc1LjA3LDYwLjIuMDUsMTIwLjQxLjA1LDE4MC42Miw1OC45OC0uMDMsMTE3Ljk3LS4wNiwxNzYuOTUsMCwyLjY4LDAsMi42LTEuMTUsMi42LTMuMDQtLjA0LTQ3Ljk0LS4wNC05NS44OC0uMDQtMTQzLjgxWk0xNDQuOTgsNjQuOTNjMTAuNTItNDQuMDgsNzIuMzUtMzYuMzcsNjQuMDksMjMuOTguMDQsMS45NC0uNzIsMi4zNC0yLjQ5LDIuMzItMTAuMDMtLjA3LTIwLjA2LS4wMy0zMC4wOC0uMDNoMGMtOS44MywwLTE5LjY1LS4wNS0yOS40OC4wNS0yLjEuMDMtMi44MS0uNTEtMi43LTIuNjguNDEtNy44OC0uNjEtMTUuOC42Ni0yMy42NVoiIGZpbGw9InVybCgjbGluZWFyLWdyYWRpZW50LTcpIiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgICA8Zz4KICAgICAgICAgIDxwYXRoIGQ9Ik0yMjAuMDQsMTU5LjE2bC02OC42Myw3NC44OWgtMjUuMTdjLTEuNzUsMC0zLjE1LTEuNzUtMi44LTMuNWwxNi43OC0xMDYuMjljLjM1LTIuNDUsMi40NS00LjIsNC44OS00LjJoNDIuNjZjMjkuMzcsMS4wNSwzNy40MSwxNi4wOCwzMi4xNywzOS4xNmwuMS0uMDdaIiBmaWxsPSIjMjQzODc5IiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgICAgIDxwYXRoIGQ9Ik0yMjEuMTYsMTUyLjI0YzEwLjQ5LDUuNTksMTIuOTQsMTYuMDgsOS40NCwzMC4wNy00LjU1LDIwLjYzLTE4LjE4LDI5LjM3LTM4LjExLDI5LjcybC01LjU5LjM1Yy0yLjEsMC0zLjUsMS40LTMuODUsMy41bC00LjU1LDI3LjYyYy0uMzUsMi40NS0yLjQ1LDQuMi00Ljg5LDQuMmgtMjAuOThjLTEuNzUsMC0zLjE1LTEuNzUtMi44LTMuNWw3LjY5LTUwYy4zNS0xLjc1LDYzLjYzLTQxLjk2LDYzLjYzLTQxLjk2WiIgZmlsbD0iIzFkOTlkNiIgc3Ryb2tlLXdpZHRoPSIwIi8+CiAgICAgICAgICA8cGF0aCBkPSJNMTU3LjExLDE5Ni42NGw2Ljk5LTQ0LjRjLjQ3LTIuMTYsMi4zNC0zLjc0LDQuNTUtMy44NWgzMy41N2M4LjA0LDAsMTMuOTksMS40LDE4Ljg4LDMuODUtMS43NSwxNS4zOC05LjA5LDQwLjIxLTQ0Ljc1LDQwLjkxaC0xNS4zOGMtMS43NSwwLTMuNSwxLjQtMy44NSwzLjVaIiBmaWxsPSIjMjMyZjVlIiBzdHJva2Utd2lkdGg9IjAiLz4KICAgICAgICA8L2c+CiAgICAgIDwvZz4KICAgIDwvZz4KICA8L2c+Cjwvc3ZnPg==",alt:"WPUSSC"});(0,M.registerBlockType)(S.UU,{icon:I,edit:function({attributes:M,setAttributes:S}){const{returnUrl:I}=M;return(0,L.createElement)(L.Fragment,null,(0,L.createElement)("div",{...(0,u.useBlockProps)()},(0,L.createElement)("p",null,"Validation Form"),(0,L.createElement)("div",{className:"text-control-wrapper"},(0,L.createElement)(N.TextControl,{className:"url",label:(0,j.__)("return url","wp-ultra-simple-paypal-shopping-cart"),value:I||"",onChange:L=>{S({returnUrl:y(L,!0)})}}))))}})}},j={};function u(L){var N=j[L];if(void 0!==N)return N.exports;var y=j[L]={exports:{}};return M[L](y,y.exports,u),y.exports}u.m=M,L=[],u.O=(M,j,N,y)=>{if(!j){var S=1/0;for(D=0;D<L.length;D++){for(var[j,N,y]=L[D],I=!0,T=0;T<j.length;T++)(!1&y||S>=y)&&Object.keys(u.O).every((L=>u.O[L](j[T])))?j.splice(T--,1):(I=!1,y<S&&(S=y));if(I){L.splice(D--,1);var i=N();void 0!==i&&(M=i)}}return M}y=y||0;for(var D=L.length;D>0&&L[D-1][2]>y;D--)L[D]=L[D-1];L[D]=[j,N,y]},u.o=(L,M)=>Object.prototype.hasOwnProperty.call(L,M),(()=>{var L={57:0,350:0};u.O.j=M=>0===L[M];var M=(M,j)=>{var N,y,[S,I,T]=j,i=0;if(S.some((M=>0!==L[M]))){for(N in I)u.o(I,N)&&(u.m[N]=I[N]);if(T)var D=T(u)}for(M&&M(j);i<S.length;i++)y=S[i],u.o(L,y)&&L[y]&&L[y][0](),L[y]=0;return u.O(D)},j=globalThis.webpackChunkcopyright_date_block=globalThis.webpackChunkcopyright_date_block||[];j.forEach(M.bind(null,0)),j.push=M.bind(null,j.push.bind(j))})();var N=u.O(void 0,[350],(()=>u(993)));N=u.O(N)})();