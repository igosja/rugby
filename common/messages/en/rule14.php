<?php
/**
 * Message translations.
 *
 * This file is automatically generated by 'yii message/extract' command.
 * It contains the localizable messages extracted from source code.
 * You may modify this file by translating the extracted messages.
 *
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of '@@' marks.
 *
 * Message string can be used with plural forms format. Check i18n section
 * of the guide for details.
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
return [
    'text' => '<p class = "strong text-justify" id = "0"> Content </p>
<p class = "text-justify">
<a href="#1"> 1. General provisions. </a>
<br/>
<a href="#1.1"> 1.1. Player cost. </a>
<br/>
<a href="#1.2"> 1.2. Player\'s salary. </a>
<br/>
<a href="#2"> 2. How are players\' salaries calculated? </a>
<br/>
<a href="#2.1"> 2.1. General information - calculation formula. </a>
<br/>
<a href="#2.2"> 2.2. Dependence of the salary on the base level. </a>
<br/>
<a href="#2.3"> 2.3. Depends on the division in which the team plays. </a>
</p>
<p class = "strong text-justify" id = "1"> 1. General provisions. </p>
<p class = "strong text-justify" id = "1.1"> 1.1. Player cost. </p>
<p class = "text-justify"> Player cost - the player\'s nominal price, depending on his age, strength, availability of special abilities and additional profile position on the field. </p>
<p class = "text-justify"> Player cost is determined by the formula: Player cost = (150 - (28 - B)) ^ 2 * (P - 1 + S + Cn) * 1000, where </p>
<ul>
<li> B - player\'s age; </li>
<li> P - the number of profile positions of the player; </li>
<li> C - player\'s nominal strength; </li>
<li> Cn - the number of the player\'s special abilities (it is calculated by adding up all the levels of various special abilities, for example, the player Sk3Km2 has 5 special abilities in total). </li>
</ul>
<p class = "text-justify"> The player\'s value determines his salary and his starting price in the transfer and rental markets. It is also taken into account when building various ratings. </p>
<p class = "text-right text-size-3"> [<a href="#0"> to content </a>] </p>
<p class = "strong text-justify" id = "1.2"> 1.2. Player\'s salary. </p>
<p class = "text-justify"> Salary is one of the costs of the team. A salary is paid to every player on the team (including those injured and on loan). </p>
<p class = "text-justify"> Salary is paid every calendar day. </p>
<p class = "text-right text-size-3"> [<a href="#0"> to content </a>] </p>
<p class = "strong text-justify" id = "2"> 2. How is player salary calculated? </p>
<p class = "strong text-justify" id = "2.1"> 2.1. General information - calculation formula. </p>
<p class = "text-justify"> The formula for calculating the salaries of the players of each team is as follows: Player\'s salary per round = Player cost * k1 * k2, where </p>
<ul>
<li> k1 - coefficient of dependence on the level of the team base; </li>
<li> k2 is the coefficient of dependence on the division in which the team is playing. </li>
</ul>
<p class = "text-right text-size-3"> [<a href="#0"> to content </a>] </p>
<p class = "strong text-justify" id = "2.2"> 2.2. Dependence of the salary on the base level. </p>
<p class = "text-justify"> The salary level is influenced by the level of the Team Base. The higher this level, the higher the salary.
<table class = "table table-hover table-bordered">
<tr> <th> Base Level </th> <th> Ratio </th> </tr>
<tr> <td class = "text-center"> 0 </td> <td class = "text-center"> 0.0003 </td> </tr>
<tr> <td class = "text-center"> 1 </td> <td class = "text-center"> 0.0004 </td> </tr>
<tr> <td class = "text-center"> 2 </td> <td class = "text-center"> 0.0005 </td> </tr>
<tr> <td class = "text-center"> 3 </td> <td class = "text-center"> 0.0006 </td> </tr>
<tr> <td class = "text-center"> 4 </td> <td class = "text-center"> 0.0007 </td> </tr>
<tr> <td class = "text-center"> 5 </td> <td class = "text-center"> 0.0008 </td> </tr>
<tr> <td class = "text-center"> 6 </td> <td class = "text-center"> 0.0009 </td> </tr>
<tr> <td class = "text-center"> 7 </td> <td class = "text-center"> 0.0010 </td> </tr>
<tr> <td class = "text-center"> 8 </td> <td class = "text-center"> 0.0011 </td> </tr>
<tr> <td class = "text-center"> 9 </td> <td class = "text-center"> 0.0012 </td> </tr>
<tr> <td class = "text-center"> 10 </td> <td class = "text-center"> 0.0013 </td> </tr>
</table>
<p class = "text-right text-size-3"> [<a href="#0"> to content </a>] </p>
<p class = "strong text-justify" id = "2.3"> 2.3. Depends on the division in which the team competes. </p>
<p class = "text-justify"> Players have higher salaries in higher divisions. </p>
<table class = "table table-hover table-bordered">
<tr><th>Division</th><th> Odds</th> </tr>
<tr> <td class = "text-center"> D1 </td> <td class = "text-center"> 1.00 </td> </tr>
<tr> <td class = "text-center"> D2 </td> <td class = "text-center"> 0.95 </td> </tr>
<tr> <td class = "text-center"> D3 </td> <td class = "text-center"> 0.90 </td> </tr>
<tr> <td class = "text-center"> D4 </td> <td class = "text-center"> 0.80 </td> </tr>
<tr> <td class = "text-center"> Conference </td> <td class = "text-center"> 0.70 </td> </tr>
</table>
<p class = "text-right text-size-3"> [<a href="#0"> to content </a>] </p> ',
    'title' => 'Player cost and salary',
];
