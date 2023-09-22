# Changelog

Mudanças relevantes no pacote serão documentadas neste arquivo.

## 1.1.0 - 21 de setembro de 2023

- Os métodos `make()` e `toFormat()` da classe `Handler` agora lançam `LogicException` quando não há nenhum arquivo selecionado.
- O método `use()` agora lança `OutOfRangeException` quando o índice selecionado é inexistente.
- Foram adicionados os métodos `first`, `last` e `exists` à classe `Handler`.
- Agora a classe `FileFormatFactory` conta com constantes de suporte para identificar tipos MIME.