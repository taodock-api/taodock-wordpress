import { execFileSync } from 'node:child_process'
import { cp, mkdir, mkdtemp, rm, stat } from 'node:fs/promises'
import { tmpdir } from 'node:os'
import path from 'node:path'
import { fileURLToPath } from 'node:url'

const projectRoot = path.dirname(fileURLToPath(import.meta.url))
const pluginSlug = 'taodock-ai-provider'
const pluginSource = path.join(projectRoot, pluginSlug)
const archive = path.join(projectRoot, 'dist', pluginSlug + '.zip')
const stagingRoot = await mkdtemp(path.join(tmpdir(), 'taodock-wordpress-build-'))

try {
  await stat(path.join(pluginSource, 'taodock-ai-provider.php'))
  await stat(path.join(pluginSource, 'readme.txt'))
  await stat(path.join(pluginSource, 'LICENSE'))
  await stat(path.join(pluginSource, 'assets', 'icon.png'))

  await cp(pluginSource, path.join(stagingRoot, pluginSlug), {
    recursive: true,
    filter(source) {
      return path.basename(source) !== '.DS_Store'
    },
  })

  await mkdir(path.dirname(archive), { recursive: true })
  await rm(archive, { force: true })
  execFileSync('zip', ['-r', '-q', archive, pluginSlug], {
    cwd: stagingRoot,
    env: { ...process.env, COPYFILE_DISABLE: '1' },
    stdio: 'inherit',
  })

  console.log('Wrote ' + path.relative(projectRoot, archive))
} finally {
  await rm(stagingRoot, { recursive: true, force: true })
}
