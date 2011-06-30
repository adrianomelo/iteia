/*
SQLyog Enterprise - MySQL GUI v7.1 
MySQL - 5.0.27-standard-log : Database - iteia_geral
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `Agenda` */

CREATE TABLE `Agenda` (
  `cod_conteudo` int(11) unsigned NOT NULL auto_increment,
  `data_inicial` date NOT NULL,
  `data_final` date NOT NULL,
  `hora_inicial` time NOT NULL,
  `hora_final` time NOT NULL,
  `local` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `telefone` varchar(50) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Albuns` */

CREATE TABLE `Albuns` (
  `cod_conteudo` int(11) NOT NULL,
  `cod_imagem_capa` int(11) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Audios` */

CREATE TABLE `Audios` (
  `cod_audio` int(11) unsigned NOT NULL auto_increment,
  `cod_conteudo` int(11) unsigned NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `tempo` varchar(20) NOT NULL,
  `ordem` int(50) NOT NULL,
  `randomico` varchar(50) NOT NULL,
  `audio` varchar(150) NOT NULL,
  `arquivo_original` varchar(255) NOT NULL,
  `tamanho` bigint(20) unsigned NOT NULL,
  `datahora` datetime NOT NULL,
  `excluido` tinyint(1) NOT NULL default '0',
  UNIQUE KEY `cod_audio` (`cod_audio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Autores` */

CREATE TABLE `Autores` (
  `cod_usuario` int(11) unsigned NOT NULL,
  `nome_completo` varchar(255) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `data_nascimento` date NOT NULL,
  `data_falecimento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `Autores_Infoextra` */

CREATE TABLE `Autores_Infoextra` (
  `cod_usuario` int(11) NOT NULL,
  `sexo` varchar(1) NOT NULL,
  `cod_etnia` int(11) NOT NULL,
  `rg` varchar(50) NOT NULL,
  PRIMARY KEY  (`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Bairros_Olinda` */

CREATE TABLE `Bairros_Olinda` (
  `cod_bairro` int(11) NOT NULL auto_increment,
  `bairro` varchar(100) NOT NULL,
  `regiao` int(11) NOT NULL,
  PRIMARY KEY  (`cod_bairro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Banners` */

CREATE TABLE `Banners` (
  `cod_banner` int(11) NOT NULL auto_increment,
  `cod_sistema` int(5) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `arquivo` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `peso` int(11) NOT NULL,
  `data_inicial` date NOT NULL,
  `data_final` date NOT NULL,
  `home` tinyint(1) NOT NULL default '0',
  `situacao` int(1) NOT NULL,
  `excluido` int(1) NOT NULL default '0',
  PRIMARY KEY  (`cod_banner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Busca_Cache` */

CREATE TABLE `Busca_Cache` (
  `id` varchar(20) NOT NULL,
  `ordem` int(20) unsigned NOT NULL auto_increment,
  `pagina` int(10) unsigned NOT NULL,
  `total` int(10) unsigned NOT NULL default '0',
  `palavra` varchar(255) default NULL,
  `tipo` varchar(15) NOT NULL default '',
  `coditem` int(10) unsigned NOT NULL,
  `data` varchar(20) NOT NULL,
  `expiracao` int(10) NOT NULL,
  PRIMARY KEY  (`ordem`),
  KEY `NewIndex1` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `Cidades` */

CREATE TABLE `Cidades` (
  `cod_cidade` int(11) NOT NULL auto_increment,
  `cidade` varchar(100) NOT NULL,
  `cod_estado` varchar(2) NOT NULL,
  PRIMARY KEY  (`cod_cidade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Colaboradores` */

CREATE TABLE `Colaboradores` (
  `cod_usuario` int(11) unsigned NOT NULL,
  `entidade` varchar(255) NOT NULL,
  `rede` varchar(255) NOT NULL,
  `administrador` tinyint(1) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `Colaboradores_Autores` */

CREATE TABLE `Colaboradores_Autores` (
  `cod_autor` int(11) default NULL,
  `cod_colaborador` int(11) default NULL,
  `datacadastro` datetime default NULL,
  `excluido` int(11) default NULL,
  KEY `NewIndex1` (`cod_autor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Colaboradores_Infoextra` */

CREATE TABLE `Colaboradores_Infoextra` (
  `cod_usuario` int(11) NOT NULL,
  `cnpj` varchar(30) NOT NULL,
  `sede_propria` tinyint(4) NOT NULL,
  `representante` varchar(200) NOT NULL,
  `historico` text NOT NULL,
  PRIMARY KEY  (`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Colaboradores_Integrantes` */

CREATE TABLE `Colaboradores_Integrantes` (
  `cod_colaborador` int(10) unsigned NOT NULL,
  `cod_autor` int(10) unsigned NOT NULL,
  `responsavel` tinyint(1) unsigned NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Comentarios_Opcoes` */

CREATE TABLE `Comentarios_Opcoes` (
  `CodSistema` tinyint(1) NOT NULL default '1',
  `PermitirPublicacao` tinyint(4) NOT NULL,
  `PrecisaAprovacao` tinyint(4) NOT NULL,
  `PalavrasModeracao` text NOT NULL,
  `PalavrasListaNegra` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Comunicadores` */

CREATE TABLE `Comunicadores` (
  `cod_comunicador` int(15) unsigned NOT NULL auto_increment,
  `comunicador` varchar(255) NOT NULL,
  UNIQUE KEY `cod_comunicador` (`cod_comunicador`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Comunicadores_Usuarios` */

CREATE TABLE `Comunicadores_Usuarios` (
  `cod_comunicador_usuario` int(15) unsigned NOT NULL auto_increment,
  `cod_comunicador` int(15) unsigned NOT NULL,
  `cod_usuario` int(15) unsigned NOT NULL,
  `cod_grupo` int(15) NOT NULL,
  `nome_usuario` varchar(255) NOT NULL,
  UNIQUE KEY `cod_comunicador_usuario` (`cod_comunicador_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo` */

CREATE TABLE `Conteudo` (
  `cod_conteudo` int(11) NOT NULL auto_increment,
  `cod_sistema` int(11) NOT NULL,
  `cod_formato` int(11) NOT NULL,
  `cod_classificacao` int(11) NOT NULL,
  `cod_segmento` int(11) NOT NULL,
  `cod_subarea` int(11) NOT NULL,
  `cod_canal` int(11) NOT NULL,
  `cod_licenca` int(11) NOT NULL,
  `cod_colaborador` int(11) NOT NULL,
  `cod_autor` int(11) NOT NULL,
  `randomico` varchar(10) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(50) NOT NULL,
  `datahora` datetime NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `situacao` tinyint(4) NOT NULL,
  `publicado` tinyint(4) NOT NULL,
  `excluido` tinyint(4) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`),
  KEY `randomico` (`randomico`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Autores` */

CREATE TABLE `Conteudo_Autores` (
  `cod_conteudo` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`,`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Autores_Ficha` */

CREATE TABLE `Conteudo_Autores_Ficha` (
  `cod_increment` int(11) NOT NULL auto_increment,
  `cod_conteudo` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `cod_atividade` int(11) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`,`cod_usuario`),
  UNIQUE KEY `cod_increment` (`cod_increment`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Classificacao` */

CREATE TABLE `Conteudo_Classificacao` (
  `cod_classificacao` int(11) unsigned NOT NULL auto_increment,
  `cod_formato` int(5) unsigned NOT NULL,
  `cod_sistema` int(1) NOT NULL default '1',
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `quantidade` int(11) NOT NULL default '0',
  `disponivel` tinyint(1) NOT NULL default '1',
  UNIQUE KEY `cod_classificacao` (`cod_classificacao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Comentarios` */

CREATE TABLE `Conteudo_Comentarios` (
  `cod_comentario` int(11) unsigned NOT NULL auto_increment,
  `cod_conteudo` int(11) unsigned NOT NULL,
  `autor` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `comentario` text NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `disponivel` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`cod_comentario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Estatisticas` */

CREATE TABLE `Conteudo_Estatisticas` (
  `cod_conteudo` int(11) unsigned NOT NULL,
  `num_recomendacoes` int(11) NOT NULL default '0',
  `num_acessos` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cod_conteudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Estatisticas_Aprovacao` */

CREATE TABLE `Conteudo_Estatisticas_Aprovacao` (
  `cod_conteudo` int(10) unsigned NOT NULL,
  `aprovado` int(10) unsigned NOT NULL default '0',
  `reprovado` int(10) unsigned NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Estatisticas_VotosNegativos` */

CREATE TABLE `Conteudo_Estatisticas_VotosNegativos` (
  `cod_conteudo` int(10) unsigned NOT NULL,
  `num_negativos` int(10) unsigned NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Formato` */

CREATE TABLE `Conteudo_Formato` (
  `cod_formato` int(11) NOT NULL auto_increment,
  `formato` varchar(100) NOT NULL,
  `midia` tinyint(4) NOT NULL,
  PRIMARY KEY  (`cod_formato`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Grupos` */

CREATE TABLE `Conteudo_Grupos` (
  `cod_conteudo` int(11) NOT NULL,
  `cod_grupo` int(11) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`,`cod_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Ips` */

CREATE TABLE `Conteudo_Ips` (
  `cod_conteudo` int(11) unsigned NOT NULL,
  `ip` varchar(50) NOT NULL,
  `tempo_saida` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_ListaPublica` */

CREATE TABLE `Conteudo_ListaPublica` (
  `cod_conteudo` int(11) unsigned NOT NULL,
  `data_cadastro` datetime NOT NULL,
  UNIQUE KEY `cod_conteudo` (`cod_conteudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `Conteudo_Notificacoes` */

CREATE TABLE `Conteudo_Notificacoes` (
  `cod_notificacao` int(11) unsigned NOT NULL auto_increment,
  `cod_tipo` int(11) unsigned NOT NULL,
  `cod_conteudo` int(11) unsigned NOT NULL,
  `cod_autor` int(11) unsigned NOT NULL,
  `cod_colaborador` int(11) unsigned NOT NULL,
  `cod_grupo` int(11) unsigned NOT NULL,
  `comentario` text NOT NULL,
  `data_cadastro` datetime NOT NULL,
  UNIQUE KEY `cod_notificacao` (`cod_notificacao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `Conteudo_Opcoes` */

CREATE TABLE `Conteudo_Opcoes` (
  `cod_conteudo` int(11) unsigned NOT NULL,
  `permitir_comentarios` smallint(1) NOT NULL default '0',
  UNIQUE KEY `cod_conteudo` (`cod_conteudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Relacionados` */

CREATE TABLE `Conteudo_Relacionados` (
  `cod_conteudo1` int(11) NOT NULL,
  `cod_conteudo2` int(11) NOT NULL,
  PRIMARY KEY  (`cod_conteudo1`,`cod_conteudo2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Reprovado` */

CREATE TABLE `Conteudo_Reprovado` (
  `cod_conteudo` int(11) unsigned NOT NULL,
  `cod_colaborador` int(11) unsigned NOT NULL,
  `motivo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `Conteudo_Revisao` */

CREATE TABLE `Conteudo_Revisao` (
  `cod_conteudo` int(11) NOT NULL,
  `cod_colaborador` int(11) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`,`cod_colaborador`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Segmento` */

CREATE TABLE `Conteudo_Segmento` (
  `cod_segmento` int(11) unsigned NOT NULL auto_increment,
  `cod_pai` int(5) unsigned NOT NULL,
  `cod_sistema` int(1) NOT NULL default '1',
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `verbete` tinyint(1) NOT NULL default '0',
  `quantidade` int(11) NOT NULL default '0',
  `disponivel` tinyint(1) NOT NULL default '1',
  UNIQUE KEY `cod_segmento` (`cod_segmento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Conteudo_Tags` */

CREATE TABLE `Conteudo_Tags` (
  `cod_conteudo` int(11) NOT NULL,
  `cod_tag` int(11) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`,`cod_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Estados` */

CREATE TABLE `Estados` (
  `cod_estado` int(3) NOT NULL auto_increment,
  `estado` varchar(100) NOT NULL,
  `sigla` varchar(2) NOT NULL,
  PRIMARY KEY  (`cod_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Etnias` */

CREATE TABLE `Etnias` (
  `cod_etnia` int(11) NOT NULL auto_increment,
  `etnia` varchar(100) NOT NULL,
  PRIMARY KEY  (`cod_etnia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Fundarpe_Colaborador_Tipo` */

CREATE TABLE `Fundarpe_Colaborador_Tipo` (
  `cod_usuario` int(11) unsigned NOT NULL,
  `tipo` int(11) NOT NULL,
  PRIMARY KEY  (`cod_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Fundarpe_Editais` */

CREATE TABLE `Fundarpe_Editais` (
  `cod_conteudo` int(11) unsigned NOT NULL auto_increment,
  `cod_categoria` tinyint(1) NOT NULL,
  `cod_tipo` tinyint(1) NOT NULL,
  `arquivo` varchar(50) NOT NULL,
  `nome_arquivo_original` varchar(255) NOT NULL,
  `tamanho` bigint(20) default NULL,
  `data_expiracao` date NOT NULL,
  PRIMARY KEY  (`cod_conteudo`)
) ENGINE=MyISAM AUTO_INCREMENT=2767 DEFAULT CHARSET=latin1;

/*Table structure for table `Fundarpe_Releases` */

CREATE TABLE `Fundarpe_Releases` (
  `cod_conteudo` int(11) unsigned NOT NULL auto_increment,
  `cod_tipo` tinyint(1) NOT NULL,
  `arquivo` varchar(50) NOT NULL,
  `nome_arquivo_original` varchar(255) NOT NULL,
  `credito` varchar(255) NOT NULL,
  `tamanho` bigint(20) default NULL,
  PRIMARY KEY  (`cod_conteudo`)
) ENGINE=MyISAM AUTO_INCREMENT=1753 DEFAULT CHARSET=latin1;

/*Table structure for table `Grupo` */

CREATE TABLE `Grupo` (
  `cod_grupo` int(15) unsigned NOT NULL auto_increment,
  `cod_colaborador` int(11) NOT NULL,
  `cod_autor` int(11) NOT NULL,
  `cod_tipo` varchar(50) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `cep` varchar(50) NOT NULL,
  `cod_pais` int(5) NOT NULL,
  `cod_estado` int(5) NOT NULL,
  `cod_cidade` int(5) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `imagem` varchar(50) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `celular` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `site` varchar(50) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `situacao` tinyint(1) NOT NULL default '0',
  `publicado` tinyint(1) NOT NULL default '0',
  `excluido` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`cod_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Grupo_Integrantes` */

CREATE TABLE `Grupo_Integrantes` (
  `cod_grupo` int(10) unsigned NOT NULL,
  `cod_autor` int(10) unsigned NOT NULL,
  `responsavel` tinyint(1) unsigned NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Grupo_Tipos` */

CREATE TABLE `Grupo_Tipos` (
  `cod_tipo` int(11) unsigned NOT NULL auto_increment,
  `cod_sistema` int(1) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `excluido` tinyint(1) NOT NULL default '0',
  UNIQUE KEY `cod_tipo` (`cod_tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Grupos` */

CREATE TABLE `Grupos` (
  `cod_usuario` int(11) unsigned NOT NULL,
  `cod_colaborador` int(11) NOT NULL,
  `cod_autor` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `num_autores` int(11) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Grupos_Agenda` */

CREATE TABLE `Grupos_Agenda` (
  `cod_grupo` int(11) unsigned NOT NULL,
  `cod_conteudo` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `Grupos_Autores` */

CREATE TABLE `Grupos_Autores` (
  `cod_grupo` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  PRIMARY KEY  (`cod_grupo`,`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Itens` */

CREATE TABLE `Home_Itens` (
  `cod_item` int(11) NOT NULL auto_increment,
  `cod_conteudo` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `pagina` smallint(6) NOT NULL,
  `destaque_posicao` tinyint(4) NOT NULL,
  `tempo_exibicao` smallint(6) NOT NULL,
  `ordem` int(11) NOT NULL,
  `data_exibicao` datetime NOT NULL,
  `excluido` tinyint(4) NOT NULL,
  PRIMARY KEY  (`cod_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Itens_Apresentacao` */

CREATE TABLE `Home_Itens_Apresentacao` (
  `cod_item` int(11) unsigned NOT NULL auto_increment,
  `cod_playlist` int(11) unsigned NOT NULL,
  `cod_conteudo` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `pagina` tinyint(1) NOT NULL,
  `destaque_posicao` tinyint(4) NOT NULL,
  `tempo_exibicao` smallint(6) NOT NULL,
  `ordem` int(11) NOT NULL,
  `data_exibicao` datetime NOT NULL,
  `disponivel` tinyint(1) NOT NULL default '0',
  `excluido` tinyint(1) NOT NULL default '0',
  UNIQUE KEY `cod_item` (`cod_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Newsletter` */

CREATE TABLE `Home_Newsletter` (
  `cod_newsletter` int(10) unsigned NOT NULL auto_increment,
  `titulo` varchar(255) NOT NULL,
  `data_inicio` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `enviada` int(1) NOT NULL default '0',
  `excluido` int(1) default '0',
  UNIQUE KEY `cod_newsletter` (`cod_newsletter`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Newsletter_Itens` */

CREATE TABLE `Home_Newsletter_Itens` (
  `cod_item` int(10) unsigned NOT NULL auto_increment,
  `cod_newsletter` int(10) unsigned NOT NULL,
  `cod_conteudo` int(10) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `credito` varchar(255) NOT NULL,
  `ordem` int(10) NOT NULL,
  `destaque` int(1) NOT NULL default '0',
  `disponivel` int(1) NOT NULL default '0',
  `excluido` int(1) NOT NULL default '0',
  UNIQUE KEY `cod_item` (`cod_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Newsletter_Listas` */

CREATE TABLE `Home_Newsletter_Listas` (
  `cod_lista` int(10) unsigned NOT NULL auto_increment,
  `titulo` varchar(255) NOT NULL,
  `data_hora` datetime NOT NULL,
  `excluido` tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `cod_lista` (`cod_lista`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Newsletter_Programacao` */

CREATE TABLE `Home_Newsletter_Programacao` (
  `cod_newsletter` int(10) unsigned NOT NULL,
  `data_envio` datetime NOT NULL,
  `enviada` tinyint(1) unsigned NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Newsletter_Programacao_Lista` */

CREATE TABLE `Home_Newsletter_Programacao_Lista` (
  `cod_newsletter` int(10) unsigned NOT NULL,
  `envio_para` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Newsletter_Usuarios` */

CREATE TABLE `Home_Newsletter_Usuarios` (
  `cod_usuario` int(11) unsigned NOT NULL auto_increment,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `data_hora` datetime NOT NULL,
  `excluido` tinyint(1) NOT NULL,
  UNIQUE KEY `cod_usuario` (`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Newsletter_Usuarios_Lista` */

CREATE TABLE `Home_Newsletter_Usuarios_Lista` (
  `cod_usuario` int(10) unsigned NOT NULL,
  `cod_lista` int(10) unsigned NOT NULL,
  KEY `cod_usuario` (`cod_usuario`,`cod_lista`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Noticias` */

CREATE TABLE `Home_Noticias` (
  `cod_conteudo` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `resumo` varchar(500) NOT NULL,
  `foto` varchar(50) NOT NULL,
  `foto_credito` varchar(255) NOT NULL,
  `foto_legenda` varchar(255) NOT NULL,
  `posicao` tinyint(4) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Home_Playlist` */

CREATE TABLE `Home_Playlist` (
  `cod_playlist` int(10) unsigned NOT NULL auto_increment,
  `cod_sistema` int(10) NOT NULL default '1',
  `cod_usuario` int(10) unsigned NOT NULL,
  `data_inicio` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `excluido` tinyint(1) NOT NULL default '0',
  UNIQUE KEY `cod_playlist` (`cod_playlist`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Imagens` */

CREATE TABLE `Imagens` (
  `cod_imagem` int(11) NOT NULL auto_increment,
  `cod_conteudo` int(11) NOT NULL,
  `randomico` varchar(10) NOT NULL,
  `imagem` varchar(50) NOT NULL,
  `legenda` varchar(255) NOT NULL,
  `credito` varchar(255) NOT NULL,
  `ordem` int(5) NOT NULL default '1',
  `capa` int(1) NOT NULL default '0',
  `datahora` datetime NOT NULL,
  `excluido` tinyint(4) NOT NULL,
  PRIMARY KEY  (`cod_imagem`),
  KEY `cod_conteudo` (`cod_conteudo`),
  KEY `randomico` (`randomico`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_AlbunsImagem_Iteia` */

CREATE TABLE `Integracao_AlbunsImagem_Iteia` (
  `cod_album_iteia` int(11) NOT NULL,
  `cod_conteudo_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Audios_Iteia` */

CREATE TABLE `Integracao_Audios_Iteia` (
  `cod_audio_iteia` int(11) NOT NULL,
  `cod_conteudo_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Autores_Fundarpe` */

CREATE TABLE `Integracao_Autores_Fundarpe` (
  `cod_autor_fundarpe` int(11) NOT NULL,
  `cod_usuario_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Autores_Iteia` */

CREATE TABLE `Integracao_Autores_Iteia` (
  `cod_autor_iteia` int(11) NOT NULL,
  `cod_usuario_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Colaboradores_Fundarpe` */

CREATE TABLE `Integracao_Colaboradores_Fundarpe` (
  `cod_colaborador_fundarpe` int(11) NOT NULL,
  `cod_usuario_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Colaboradores_Iteia` */

CREATE TABLE `Integracao_Colaboradores_Iteia` (
  `cod_colaborador_iteia` int(11) NOT NULL,
  `cod_usuario_penc` int(11) NOT NULL,
  `cod_autor_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Conteudo_Classificacao_Fundarpe` */

CREATE TABLE `Integracao_Conteudo_Classificacao_Fundarpe` (
  `cod_classificacao_fundarpe` int(11) NOT NULL,
  `cod_classificacao_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Conteudo_Fundarpe` */

CREATE TABLE `Integracao_Conteudo_Fundarpe` (
  `cod_conteudo_fundarpe` int(11) NOT NULL,
  `cod_conteudo_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Conteudo_Segmento_Fundarpe` */

CREATE TABLE `Integracao_Conteudo_Segmento_Fundarpe` (
  `cod_segmento_fundarpe` int(11) NOT NULL,
  `cod_segmento_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Grupos_Fundarpe` */

CREATE TABLE `Integracao_Grupos_Fundarpe` (
  `cod_grupo_fundarpe` int(11) NOT NULL,
  `cod_usuario_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Noticias_Iteia` */

CREATE TABLE `Integracao_Noticias_Iteia` (
  `cod_noticia_iteia` int(11) NOT NULL,
  `cod_conteudo_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Tags_Fundarpe` */

CREATE TABLE `Integracao_Tags_Fundarpe` (
  `cod_tag_fundarpe` int(11) NOT NULL,
  `cod_tag_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Textos_Iteia` */

CREATE TABLE `Integracao_Textos_Iteia` (
  `cod_texto_iteia` int(11) NOT NULL,
  `cod_conteudo_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Integracao_Videos_Iteia` */

CREATE TABLE `Integracao_Videos_Iteia` (
  `cod_video_iteia` int(11) NOT NULL,
  `cod_conteudo_penc` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Licencas` */

CREATE TABLE `Licencas` (
  `cod_licenca` int(11) NOT NULL,
  `ordem` int(1) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `disponivel` smallint(1) default '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Links` */

CREATE TABLE `Links` (
  `cod_link` int(11) NOT NULL auto_increment,
  `cod_usuario` int(11) NOT NULL,
  `cod_grupo` int(11) NOT NULL,
  `site` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  UNIQUE KEY `cod_link` (`cod_link`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Newsletters_Usuarios_Fundarpe` */

CREATE TABLE `Newsletters_Usuarios_Fundarpe` (
  `cod_email` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  UNIQUE KEY `cod_email` (`cod_email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Noticias` */

CREATE TABLE `Noticias` (
  `cod_conteudo` int(11) NOT NULL,
  `subtitulo` varchar(255) NOT NULL,
  `assinatura` varchar(255) NOT NULL,
  `foto_ampliada` varchar(50) NOT NULL,
  `foto_credito` varchar(255) NOT NULL,
  `foto_legenda` varchar(255) NOT NULL,
  `home` tinyint(4) NOT NULL,
  `secao` tinyint(4) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Noticias_Migracao` */

CREATE TABLE `Noticias_Migracao` (
  `cod_noticia_fundarpe` int(10) unsigned NOT NULL,
  `cod_noticia_penc` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Noticias_Migracao2` */

CREATE TABLE `Noticias_Migracao2` (
  `cod_noticia_fundarpe` int(11) NOT NULL,
  `cod_noticia_penc` int(11) NOT NULL,
  PRIMARY KEY  (`cod_noticia_fundarpe`,`cod_noticia_penc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Paises` */

CREATE TABLE `Paises` (
  `cod_pais` int(10) unsigned NOT NULL auto_increment,
  `pais` varchar(100) NOT NULL,
  PRIMARY KEY  (`cod_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Segmentos_Areas` */

CREATE TABLE `Segmentos_Areas` (
  `cod_area` int(11) NOT NULL auto_increment,
  `area` varchar(100) NOT NULL,
  `cod_area_pai` int(11) NOT NULL,
  PRIMARY KEY  (`cod_area`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Tags` */

CREATE TABLE `Tags` (
  `cod_tag` int(11) NOT NULL auto_increment,
  `cod_sistema` tinyint(1) NOT NULL,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY  (`cod_tag`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Textos` */

CREATE TABLE `Textos` (
  `cod_conteudo` int(11) NOT NULL,
  `arquivo` varchar(50) NOT NULL,
  `nome_arquivo_original` varchar(255) NOT NULL,
  `tamanho` bigint(20) NOT NULL,
  `foto_credito` varchar(255) NOT NULL,
  `foto_legenda` varchar(255) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Urls` */

CREATE TABLE `Urls` (
  `titulo` varchar(150) NOT NULL,
  `cod_item` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `cod_sistema` int(11) NOT NULL,
  PRIMARY KEY  (`titulo`,`cod_sistema`),
  KEY `cod_item` (`cod_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `Usuarios` */

CREATE TABLE `Usuarios` (
  `cod_usuario` int(11) unsigned NOT NULL auto_increment,
  `cod_sistema` int(5) NOT NULL default '1',
  `cod_tipo` int(1) NOT NULL default '1',
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `cep` varchar(25) NOT NULL,
  `cod_pais` int(5) NOT NULL,
  `cod_estado` int(5) NOT NULL,
  `cod_cidade` int(5) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mostrar_email` tinyint(1) NOT NULL default '1',
  `site` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `situacao` tinyint(1) NOT NULL,
  `datacadastro` datetime NOT NULL,
  `disponivel` tinyint(1) NOT NULL default '1',
  UNIQUE KEY `cod_usuario` (`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `Usuarios_Atividades` */

CREATE TABLE `Usuarios_Atividades` (
  `cod_atividade` int(11) unsigned NOT NULL auto_increment,
  `cod_sistema` int(1) NOT NULL,
  `atividade` varchar(255) NOT NULL,
  `excluido` tinyint(1) NOT NULL default '0',
  UNIQUE KEY `cod_atividade` (`cod_atividade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Usuarios_Documentos` */

CREATE TABLE `Usuarios_Documentos` (
  `cod_usuario` int(10) unsigned NOT NULL,
  `cod_tipo` tinyint(1) unsigned NOT NULL,
  `numero` varchar(255) NOT NULL,
  `orgao` varchar(10) default NULL,
  PRIMARY KEY  (`cod_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `Usuarios_Estatisticas` */

CREATE TABLE `Usuarios_Estatisticas` (
  `cod_usuario` int(11) NOT NULL,
  `videos` int(11) default '0',
  `audios` int(11) default '0',
  `textos` int(11) default '0',
  `imagens` int(11) default '0',
  `autores` int(11) default '0',
  `noticias` int(11) default '0',
  PRIMARY KEY  (`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Usuarios_Infoextra` */

CREATE TABLE `Usuarios_Infoextra` (
  `cod_usuario` int(11) NOT NULL,
  `cod_bairro` int(11) NOT NULL,
  PRIMARY KEY  (`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Usuarios_Informacoes` */

CREATE TABLE `Usuarios_Informacoes` (
  `cod_usuario` int(11) unsigned NOT NULL,
  `interesses` varchar(255) NOT NULL,
  `cpf` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Usuarios_Niveis` */

CREATE TABLE `Usuarios_Niveis` (
  `cod_usuario` int(11) NOT NULL,
  `nivel` int(11) NOT NULL,
  PRIMARY KEY  (`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Usuarios_Segmentos` */

CREATE TABLE `Usuarios_Segmentos` (
  `cod_usuario` int(11) NOT NULL,
  `cod_segmento` int(11) NOT NULL,
  PRIMARY KEY  (`cod_usuario`,`cod_segmento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Usuarios_Telefones` */

CREATE TABLE `Usuarios_Telefones` (
  `cod_usuariotel` int(11) NOT NULL auto_increment,
  `cod_usuario` int(11) NOT NULL,
  `telefone` varchar(30) NOT NULL,
  `tipo_telefone` tinyint(4) NOT NULL,
  PRIMARY KEY  (`cod_usuariotel`),
  KEY `cod_usuario` (`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Usuarios_Validacao` */

CREATE TABLE `Usuarios_Validacao` (
  `cod_usuario` int(10) unsigned NOT NULL,
  `datavalidacao` datetime NOT NULL,
  `comprovante_atuacao` int(1) NOT NULL default '0',
  `comprovante_residencia` int(1) NOT NULL default '0',
  `comprovante_cnpj` int(1) NOT NULL default '0',
  `xerox_identidade` int(1) NOT NULL default '0',
  `xerox_cpf` int(1) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Videos` */

CREATE TABLE `Videos` (
  `cod_conteudo` int(11) NOT NULL,
  `arquivo` varchar(50) NOT NULL,
  `arquivo_original` varchar(50) NOT NULL,
  `tamanho` bigint(20) NOT NULL,
  `link` varchar(200) NOT NULL,
  PRIMARY KEY  (`cod_conteudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `Videos_Conversao` */

CREATE TABLE `Videos_Conversao` (
  `cod_video` int(11) NOT NULL auto_increment,
  `arquivo_original` varchar(200) NOT NULL,
  `arquivo_convertido` varchar(50) NOT NULL,
  `data_entrada` datetime NOT NULL,
  `data_saida` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `erro` varchar(255) NOT NULL,
  PRIMARY KEY  (`cod_video`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `v_autores` */

DROP VIEW IF EXISTS `v_autores`;

CREATE VIEW `v_autores` AS (SELECT t1.cod_usuario, t1.cod_tipo, t1.disponivel, t1.situacao, t1.cod_sistema, t1.datacadastro, t1.nome, t1.descricao, t1.imagem, t2.cod_estado, t2.sigla, t3.titulo AS url, SUM(t5.videos+t5.audios+t5.textos+t5.imagens) AS geral, t6.cod_cidade, t6.cidade FROM Usuarios AS t1 LEFT JOIN Estados AS t2 ON (t1.cod_estado=t2.cod_estado) LEFT JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) LEFT JOIN Usuarios_Estatisticas AS t5 ON (t1.cod_usuario=t5.cod_usuario) LEFT JOIN Cidades AS t6 ON (t1.cod_cidade=t6.cod_cidade) WHERE t1.disponivel='1' AND t1.situacao='3' AND t1.cod_tipo='2' AND t3.tipo='2' GROUP BY t5.cod_usuario)
		
/*Table structure for table `v_colaboradores` */

DROP VIEW IF EXISTS `v_colaboradores`;

CREATE VIEW `v_colaboradores` AS (SELECT t1.cod_usuario, t1.cod_tipo, t1.disponivel, t1.situacao, t1.cod_sistema, t1.datacadastro, t1.nome, t1.descricao, t1.imagem, t2.cod_estado, t2.sigla, t3.titulo AS url, SUM(t5.videos+t5.audios+t5.textos+t5.imagens) AS geral, t6.cod_cidade, t6.cidade FROM Usuarios AS t1 LEFT JOIN Estados AS t2 ON (t1.cod_estado=t2.cod_estado) LEFT JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) LEFT JOIN Usuarios_Estatisticas AS t5 ON (t1.cod_usuario=t5.cod_usuario) LEFT JOIN Cidades AS t6 ON (t1.cod_cidade=t6.cod_cidade) WHERE t1.disponivel='1' AND t1.situacao='3' AND t1.cod_tipo='1' AND t3.tipo='1' GROUP BY t5.cod_usuario);

/*Table structure for table `v_grupos` */

DROP VIEW IF EXISTS `v_grupos`;

CREATE VIEW `v_grupos` AS (SELECT t1.cod_usuario, t1.cod_tipo, t1.disponivel, t1.situacao, t1.cod_sistema, t1.datacadastro, t1.nome, t1.descricao, t1.imagem, t2.cod_estado, t2.sigla, t3.titulo AS url, COUNT(t4.cod_conteudo) AS geral, t6.cod_cidade, t6.cidade FROM Usuarios AS t1 LEFT JOIN Estados AS t2 ON (t1.cod_estado=t2.cod_estado) LEFT JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) LEFT JOIN Conteudo_Grupos AS t4 ON (t1.cod_usuario=t4.cod_grupo) LEFT JOIN Cidades AS t6 ON (t1.cod_cidade=t6.cod_cidade) WHERE t1.disponivel='1' AND t1.situacao='3' AND t1.cod_tipo='3' AND t3.tipo='3' GROUP BY t1.cod_usuario);
