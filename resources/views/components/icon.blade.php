@props(['index'=>0])
<svg viewBox="0 0 48 48" aria-hidden="true">
<path d="M8 9h32v30H8z"/>
@switch($index)
@case(0)<path d="M24 9v30M8 24h32M12 35l8-8M28 20l8-8"/>@break
@case(1)<path d="M8 20h32M24 20v19M18 27v5M30 27v5M13 39v4M35 39v4"/>@break
@case(2)<path d="M8 19h32M8 29h32M18 9v10M30 19v10M20 29v10"/>@break
@case(3)<path d="M24 9v30M8 24h32M8 9l32 30M40 9L8 39"/>@break
@default <path d="M14 9v30M24 9v30M34 9v30M5 15h38M5 35h38"/>
@endswitch</svg>