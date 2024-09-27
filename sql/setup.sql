-- Criar tabela de setores
CREATE TABLE setores (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

-- Criar tabela de dispositivos
CREATE TABLE dispositivos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    status BOOLEAN DEFAULT TRUE
);

-- Criar tabela de perguntas
CREATE TABLE perguntas (
    id SERIAL PRIMARY KEY,
    texto TEXT NOT NULL,
    status BOOLEAN DEFAULT TRUE
);

-- Criar tabela de avaliações
CREATE TABLE avaliacoes (
    id SERIAL PRIMARY KEY,
    id_setor INT REFERENCES setores(id) ON DELETE CASCADE,
    id_pergunta INT REFERENCES perguntas(id) ON DELETE CASCADE,
    id_dispositivo INT REFERENCES dispositivos(id) ON DELETE CASCADE,
    resposta INT CHECK (resposta >= 0 AND resposta <= 10) NOT NULL,
    feedback TEXT,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- Criar tabela de usuários administrativos
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    login VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);
