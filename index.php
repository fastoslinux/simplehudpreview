<?php

$show_preview = false;
$preview_image = '';
$config = '';

// Definindo valores padrão
$action = '';
$font_scale = '1.0';
$fps_limit = '0';
$position = 'top-left';
$layout = 'default';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';
    $font_scale = $_POST['font_scale'] ?? '1.0';
    $fps_limit = $_POST['fps_limit'] ?? '0';
    $position = $_POST['position'] ?? 'top-left';
    $layout = $_POST['layout'] ?? 'default';

    // Sanitiza os valores
    $font_scale = floatval($font_scale);
    $fps_limit = intval($fps_limit);
    $position = htmlspecialchars($position);
    $layout = htmlspecialchars($layout);

    // Monta o conteúdo do arquivo
    $config = "font_scale={$font_scale}\n";
    $config .= "fps_limit={$fps_limit}\n";
    $config .= "position={$position}\n";

    if ($layout === 'horizontal') {
        $config .= "horizontal\n";
    } else {
        $config .= "legacy_layout=false\n";
    }

    // Adiciona bloco de opções extras se for layout default
    if ($layout === 'default') {
        $config .= <<<EXTRA

gpu_stats
gpu_temp
gpu_load_change
gpu_load_value=50,90
gpu_load_color=FFFFFF,FFAA7F,CC0000
gpu_text=GPU
gpu_color=2e9762

cpu_stats
cpu_temp
cpu_load_change
core_load_change
cpu_load_value=50,90
cpu_load_color=FFFFFF,FFAA7F,CC0000
cpu_color=2e97cb
cpu_text=CPU

io_color=a491d3

vram
vram_color=FEBD9D

ram
ram_color=FEBD9D

fps
fps_color_change
fps_value=30,60,144
fps_color=b22222,fdfd09,39f900

engine_color=eb5b5b

wine_color=eb5b5b
frame_timing=1
frametime_color=00ff00
media_player_color=ffffff
background_alpha=0.4

background_color=020202
text_color=ffffff
round_corners=10

toggle_hud=F1

EXTRA;
}

if ($layout === 'vertical') {
    $config .= <<<VERTICAL

round_corners=10.0

gpu_stats
gpu_temp
gpu_core_clock
gpu_mem_clock
gpu_power
gpu_load_change
gpu_load_value=50,90
gpu_load_color=FFFFFF,FFAA7F,CC0000
gpu_text=GPU
cpu_stats
cpu_temp
core_load
cpu_power
cpu_mhz
cpu_load_change
core_load_change
cpu_load_value=50,90
cpu_load_color=FFFFFF,FFAA7F,CC0000
cpu_color=2e97cb
cpu_text=CPU
io_stats
io_read
io_write
io_color=a491d3
swap
vram
vram_color=ad64c1
ram
ram_color=c26693

fps
fps_color_change
fps_value=30,60,144
fps_color=b22222,fdfd09,39f900
fps_metrics=avg,0.01,0.001

engine_version
engine_color=eb5b5b
gpu_name
gpu_color=2e9762
vulkan_driver
arch
wine
wine_color=eb5b5b
frame_timing=1
frametime_color=00ff00
show_fps_limit
resolution
gamemode
gamepad_battery
gamepad_battery_icon
battery


toggle_hud=F1



VERTICAL;
    }

if ($layout === 'horizontal'){
    $config .= <<<HORIZONTAL

legacy_layout=0
table_columns=20
background_alpha=0
    
    
gpu_stats
gpu_temp
gpu_load_change
gpu_load_value=50,90
gpu_load_color=FFFFFF,FFAA7F,CC0000
gpu_text=GPU
gpu_color=FEBD9D
gpu_core_clock
    
cpu_stats
cpu_temp
cpu_load_change
core_load_change
cpu_load_value=50,90
cpu_load_color=FFFFFF,FFAA7F,CC0000
cpu_color=FEBD9D
cpu_text=CPU
cpu_mhz
    
vram
vram_color=FFAA7F
    
ram
ram_color=62A0EA
    
fps
fps_color_change
fps_value=30,60,144
fps_color=b22222,fdfd09,39f900
    
engine_color=FFAA7F
    
    
frame_timing=1
frametime_color=00ff00
background_alpha=0.4
gamemode
device_battery=gamepad
gamepad_battery_icon
vulkan_driver
position=top-left-left
round_corners=10
    toggle_hud=F1
    

HORIZONTAL;}

        // Se for ação de preview
        if ($action === 'preview') {
            $show_preview = true;
        
            $base_url = 'https://github.com/fastoslinux/simplehudpreview/blob/main/';

            switch ($layout) {
                case 'vertical':
                    $preview_image = $base_url . 'complete.png?raw=true';
                    break;
                case 'horizontal':
                    $preview_image = $base_url . 'horizontal.png?raw=true';
                    break;
                case 'default':
                default:
                    $preview_image = $base_url . 'default.png?raw=true';
                    break;
            }
        }
        

        // Se for ação de download
        if ($action === 'generate') {
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="MangoHud.conf"');
            header('Content-Length: ' . strlen($config));
            echo $config;
            exit;
        }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple HUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 40px;">
        <form method="POST" style="flex: 1;">
        <h3>Select Layout</h3>
        <label><input type="radio" name="layout" value="default" <?php if ($layout === 'default') echo 'checked'; ?>> Default Vertical Minimal</label><br><br>
        <label><input type="radio" name="layout" value="vertical" <?php if ($layout === 'vertical') echo 'checked'; ?>> Vertical Complete</label><br><br>
        <label><input type="radio" name="layout" value="horizontal" <?php if ($layout === 'horizontal') echo 'checked'; ?>> Horizontal</label><br><br>
        <button type="submit" name="action" value="preview">Preview</button><br><br>
        
        <h3>Select Position</h3>
        <label><input type="radio" name="position" value="top-left" checked> top left</label><br><br>
        <label><input type="radio" name="position" value="top-right"> top right</label><br><br>
        <label><input type="radio" name="position" value="middle-left"> middle left</label><br><br>
        <label><input type="radio" name="position" value="middle-right"> middle right</label><br><br>
        <label><input type="radio" name="position" value="bottom-left"> bottom left</label><br><br>
        <label><input type="radio" name="position" value="bottom-right"> bottom right</label><br><br>
        <label><input type="radio" name="position" value="top-center"> top center</label><br><br>
        <label><input type="radio" name="position" value="bottom-center"> bottom-center</label><br><br>

        <label for="font_scale">Font Scale:</label>
        <input type="range" id="font_scale" name="font_scale" min="0.1" max="2.0" step="0.1" value="1.0" oninput="document.getElementById('font_scale_value').textContent = this.value">
        <span id="font_scale_value">1.0</span>
        <br><br>

        <label for="fps_limit">FPS Limit (0 = unlimited):</label>
        <input type="number" id="fps_limit" name="fps_limit" min="0" max="999" value="0">
        <br><br>

        
        <button type="submit" name="action" value="generate">Download MangoHud.conf</button>


    </form>

   


    <?php if ($show_preview): ?>
        <div class="layoutpreview">
            <h3>Layout Preview:</h3>
            <img src="<?php echo $preview_image; ?>" alt="Layout Preview" style="max-width: 100%; height: auto;">
        </div>
    <?php endif; ?>

    <p class="note">
        <strong>Note:</strong> After downloading the <code>MangoHud.conf</code> file, place it in the following folder:<br>
        <code>~/.config/MangoHud/</code><br>
        For example:<br>
        <code>/var/home/myuser/.config/MangoHud/MangoHud.conf</code>
    </p>

</body>

</html>
