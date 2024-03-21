<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrixáforo</title>
    <link rel="icon" href="hello.gif">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: black;
            overflow: hidden; 
        }

        canvas {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1; 
        }

    </style>

</head>
<body>
<canvas id="matrix"></canvas>

<audio id="audio" src="som.mp3"></audio>

<script>

    const c = document.getElementById("matrix");
    const ctx = c.getContext("2d");
    c.height = window.innerHeight;
    c.width = window.innerWidth;

    const letters = [
        "日", "ﾊ", "ﾐ", "ﾋ", "ｰ", "ｳ", "ｼ", "ﾅ", "ﾓ", "ﾆ", 
        "ｻ", "ﾜ", "ﾂ", "ｵ", "ﾘ", "ｱ", "ﾎ", "ﾃ", "ﾏ", "ｹ", 
        "ﾒ", "ｴ", "ｶ", "ｷ", "ﾑ", "ﾕ", "ﾗ", "ｾ", "ﾈ", "ｽ", 
        "ﾀ", "ﾇ", "ﾍ", ":", "・", ".", "=", "*", "+", "-", 
        "<", ">", "¦", "｜", "ﾘ"
    ];

    const fontSize = 15;
    const columns = Math.floor(c.width / fontSize); 
    const drops = [];
    const startingXPositions = [];
    let lastRes = ''; 

    const audio = document.getElementById("audio"); 

    for (let x = 0; x < columns; x++) {
        drops[x] = Math.floor(Math.random() * c.height);
        startingXPositions[x] = x * fontSize; 
    }

    const dropSpeed = 0.6; 

    function draw(color) {
        ctx.fillStyle = "rgba(0, 0, 0, 0.05)"; 
        ctx.fillRect(0, 0, c.width, c.height);

        ctx.fillStyle = `rgb(${color[0]}, ${color[1]}, ${color[2]})`;
        ctx.font = `${fontSize}px arial`;

        for (let i = 0; i < drops.length; i++) {
            const text = letters[Math.floor(Math.random() * letters.length)];
            const yPosition = drops[i] * fontSize;
            ctx.fillText(text, startingXPositions[i], yPosition);

            if (yPosition > c.height) {
                drops[i] = Math.floor(Math.random() * c.height); 
            } else {
                drops[i] += dropSpeed;
            }
        }

        requestAnimationFrame(() => draw(color));
    }

    function fetchColor() {
        fetch('https://niloweb.com.br/transit-room/api/reg_endpoint.php')
            .then(response => response.json())
            .then(data => {
                const color = getColor(data[0].res);
                if (lastRes !== color.toString()) {
                    lastRes = color.toString();
                    draw(color);
                }
            })
            .catch(error => console.error('Error fetching API:', error))
            .finally(() => {
                setTimeout(fetchColor, 5000);
                playSound(); 
            });
    }

    function getColor(res) {
        switch (res) {
            case 'L':
                return [0, 255, 0]; // verde
            case 'B':
                return [255, 0, 0]; // vermelho
            case 'A':
                return [255, 255, 0]; // amarelo
            default:
                return [255, 255, 255]; // branco padrão
        }   
    }

    function playSound() {
        audio.currentTime = 0; 
        audio.play(); 
    }

    fetchColor();
</script>

</body>
</html>
