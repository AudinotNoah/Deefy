<?php

namespace iutnc\deefy\action;

class DefaultAction extends Action {
    
    protected function get(): string
    {   
        return "<header>
                <h1>Bienvenue sur Deefy</h1>
            </header>";
    }
}
